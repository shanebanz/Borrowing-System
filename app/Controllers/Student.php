<?php

namespace App\Controllers;

use App\Models\EquipmentModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use CodeIgniter\Email\Email;

class Student extends BaseController
{
    public function __construct()
    {
        helper('auth');
        check_student_access();
    }

    public function dashboard()
    {
        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();
        $user_id = session()->get('user_id');

        $data = [
            'title' => 'Student Dashboard',
            'user' => session()->get(),
            'availableItems' => $equipmentModel->getAvailableItems(),
            'activeItems' => $transactionModel->getActiveBorrowedItemsPaginated($user_id, 8),
            'pager' => $transactionModel->pager,
            'pendingCount' => $transactionModel->countPending($user_id),
            'returnedCount' => $transactionModel->countReturned($user_id),
        ];

        return view('studentDash_main', $data);
    }

    public function getAvailableItems()
    {
        $model = new EquipmentModel();
        return $this->response->setJSON($model->getAvailableItems());
    }

    public function getItemStock($id)
    {
        $equipmentModel = new EquipmentModel();
        $item = $equipmentModel->find($id);

        return $this->response->setJSON([
            'available_quantity' => $item['available_quantity'] ?? 0
        ]);
    }

    public function getUserBorrowedQty($equipment_id)
    {
        $transactionModel = new TransactionModel();

        $transactions = $transactionModel
                ->where('user_id', session()->get('user_id'))
                ->where('equipment_id', $equipment_id)
                ->where('returned', 0)
                ->findAll();

        $totalQty = 0;
        foreach ($transactions as $transaction) {
            $totalQty += $transaction['quantity'];
        }

        return $this->response->setJSON([
            'borrowed' => $totalQty
        ]);
    }

    public function process()
    {
        $equipment_id = $this->request->getPost('item');
        $qty = max(1, (int)$this->request->getPost('quantity')); // ensure positive int
        $action = $this->request->getPost('action');
        $user_id = session()->get('user_id');

        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();

        $equipment = $equipmentModel->find($equipment_id);
        if (!$equipment) {
            return redirect()->back()->with('error', 'Invalid item');
        }

        // ============ BORROW ============
        if ($action === 'borrow') {
            if ($equipment['available_quantity'] < $qty) {
                return redirect()->back()->with('error', 'Not enough stock available');
            }

            // Create single transaction record with quantity
            $transactionModel->insert([
                'user_id' => $user_id,
                'equipment_id' => $equipment_id,
                'quantity' => $qty,
                'borrow_date' => date('Y-m-d H:i:s'),
                'returned' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $equipmentModel->update($equipment_id, [
                'available_quantity' => $equipment['available_quantity'] - $qty
            ]);

            // Send email notification
            $this->sendBorrowEmail($equipment, $qty);

            return redirect()->to('/studentDash_main')->with('success', 'Borrow request successful!');
        }

        // ============ RETURN ============
        if ($action === 'return') {
            $borrowed = $transactionModel
                ->where('user_id', $user_id)
                ->where('equipment_id', $equipment_id)
                ->where('returned', 0)
                ->orderBy('borrow_date', 'ASC')
                ->findAll();

            if (empty($borrowed)) {
                return redirect()->back()->with('error', 'No borrowed items to return');
            }

            $totalBorrowed = 0;
            foreach ($borrowed as $item) {
                $totalBorrowed += $item['quantity'];
            }

            if ($qty > $totalBorrowed) {
                $qty = $totalBorrowed;
            }

            $remaining = $qty;
            foreach ($borrowed as $item) {
                if ($remaining <= 0) break;

                if ($item['quantity'] <= $remaining) {
                    // Return entire transaction
                    $transactionModel->update($item['borrow_id'], [
                        'returned' => 1,
                        'return_date' => date('Y-m-d H:i:s')
                    ]);
                    $remaining -= $item['quantity'];
                } else {
                    // Partial return - reduce quantity
                    $transactionModel->update($item['borrow_id'], [
                        'quantity' => $item['quantity'] - $remaining
                    ]);
                    // Create returned transaction
                    $transactionModel->insert([
                        'user_id' => $user_id,
                        'equipment_id' => $equipment_id,
                        'quantity' => $remaining,
                        'borrow_date' => $item['borrow_date'],
                        'return_date' => date('Y-m-d H:i:s'),
                        'returned' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $remaining = 0;
                }
            }

            $equipmentModel->update($equipment_id, [
                'available_quantity' => $equipment['available_quantity'] + $qty
            ]);

            // Send email notification
            $this->sendReturnEmail($equipment, $qty);

            return redirect()->to('/studentDash_main')->with('success', 'Return successful!');
        }

        return redirect()->back();
    }

    public function returnEquipment()
    {
        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();

        $transactionId = $this->request->getPost('transaction_id');

        if (!$transactionId) {
            return redirect()->back()->with('error', 'Invalid transaction.');
        }

        $transaction = $transactionModel->find($transactionId);

        if (!$transaction || $transaction['user_id'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'Transaction not found or unauthorized.');
        }

        if ($transaction['returned'] == 1) {
            return redirect()->back()->with('error', 'Equipment already returned.');
        }

        // Mark as returned
        $transactionModel->update($transactionId, [
            'returned' => 1,
            'return_date' => date('Y-m-d H:i:s')
        ]);

        // Update available quantity
        $equipment = $equipmentModel->find($transaction['equipment_id']);
        $newAvailable = $equipment['available_quantity'] + $transaction['quantity'];
        $equipmentModel->update($transaction['equipment_id'], ['available_quantity' => $newAvailable]);

        // Send return email notification
        $this->sendReturnEmail($equipment, $transaction['quantity']);

        return redirect()->to('/studentDash_main')->with('success', 'Equipment returned successfully! Check your email for confirmation.');
    }

    private function sendBorrowEmail($equipment, $quantity)
    {
        $email = \Config\Services::email();
        
        $userEmail = session()->get('email');
        $userName = session()->get('firstname') . ' ' . session()->get('lastname');
        $equipmentName = $equipment['name'];
        $category = $equipment['category'];
        $accessories = !empty($equipment['accessories']) ? $equipment['accessories'] : 'None';
        $borrowDate = date('F j, Y g:i A');

        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #176734; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; }
                .details { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #176734; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
                h2 { color: #176734; margin-top: 0; }
                .label { font-weight: bold; color: #176734; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Equipment Borrowed Successfully</h1>
                </div>
                <div class='content'>
                    <p>Dear {$userName},</p>
                    <p>This email confirms that you have successfully borrowed equipment from the IT Services Office.</p>
                    
                    <div class='details'>
                        <h2>Borrowing Details</h2>
                        <p><span class='label'>Equipment:</span> {$equipmentName}</p>
                        <p><span class='label'>Category:</span> {$category}</p>
                        <p><span class='label'>Quantity:</span> {$quantity}</p>
                        <p><span class='label'>Accessories:</span> {$accessories}</p>
                        <p><span class='label'>Borrowed on:</span> {$borrowDate}</p>
                    </div>
                    
                    <p>Please take good care of the equipment and return it in the same condition.</p>
                    <p>If you have any questions, please contact the IT Services Office.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from the IT Services Equipment Management System.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $email->setTo($userEmail);
        $email->setFrom('norwood0602@gmail.com', 'IT Services Office');
        $email->setSubject('Equipment Borrowed - ' . $equipmentName);
        $email->setMessage($message);

        try {
            $email->send();
        } catch (\Exception $e) {
            log_message('error', 'Failed to send borrow email: ' . $e->getMessage());
        }
    }

    private function sendReturnEmail($equipment, $quantity)
    {
        $email = \Config\Services::email();
        
        $userEmail = session()->get('email');
        $userName = session()->get('firstname') . ' ' . session()->get('lastname');
        $equipmentName = $equipment['name'];
        $category = $equipment['category'];
        $returnDate = date('F j, Y g:i A');

        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #176734; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f9f9f9; padding: 20px; margin-top: 20px; }
                .details { background-color: white; padding: 15px; margin: 15px 0; border-left: 4px solid #176734; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
                h2 { color: #176734; margin-top: 0; }
                .label { font-weight: bold; color: #176734; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Equipment Returned Successfully</h1>
                </div>
                <div class='content'>
                    <p>Dear {$userName},</p>
                    <p>This email confirms that you have successfully returned equipment to the IT Services Office.</p>
                    
                    <div class='details'>
                        <h2>Return Details</h2>
                        <p><span class='label'>Equipment:</span> {$equipmentName}</p>
                        <p><span class='label'>Category:</span> {$category}</p>
                        <p><span class='label'>Quantity:</span> {$quantity}</p>
                        <p><span class='label'>Returned on:</span> {$returnDate}</p>
                    </div>
                    
                    <p>Thank you for returning the equipment on time and in good condition.</p>
                    <p>If you need to borrow equipment again, feel free to visit the IT Services Office.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from the IT Services Equipment Management System.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $email->setTo($userEmail);
        $email->setFrom('norwood0602@gmail.com', 'IT Services Office');
        $email->setSubject('Equipment Returned - ' . $equipmentName);
        $email->setMessage($message);

        try {
            $email->send();
        } catch (\Exception $e) {
            log_message('error', 'Failed to send return email: ' . $e->getMessage());
        }
    }
}
