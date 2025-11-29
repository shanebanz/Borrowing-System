<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EquipmentModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\ReservationModel;

class Associate extends BaseController
{
    public function __construct()
    {
        helper('auth');
        check_associate_access();
    }

    public function dashboard()
    {
        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();
        $user_id = session()->get('user_id');

        $data = [
            'title' => 'Associates Dashboard',
            'user' => [
                'name' => session()->get('firstname') . ' ' . session()->get('lastname'),
                'role' => session()->get('role')
            ],
            'availableEquipment' => $equipmentModel->where('is_active', 1)
                                                   ->where('available_quantity >', 0)
                                                   ->orderBy('category', 'ASC')
                                                   ->orderBy('name', 'ASC')
                                                   ->findAll(),
            'borrowedItems' => $transactionModel->getActiveBorrowedItems($user_id),
            'borrowedCount' => $transactionModel->countPending($user_id),
            'returnedCount' => $transactionModel->countReturned($user_id)
        ];
        return view('assoDash_main', $data);
    }

    public function getEquipmentDetails($id)
    {
        $equipmentModel = new EquipmentModel();
        $equipment = $equipmentModel->find($id);

        if ($equipment) {
            return $this->response->setJSON([
                'success' => true,
                'equipment' => $equipment
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Equipment not found'
            ]);
        }
    }

    public function borrowEquipment()
    {
        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();
        $userModel = new UserModel();

        $rules = [
            'equipment_id' => 'required|numeric',
            'quantity' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid input. Please try again.')->withInput();
        }

        $equipmentId = $this->request->getPost('equipment_id');
        $quantity = (int)$this->request->getPost('quantity');
        $userId = session()->get('user_id');

        // Get equipment details
        $equipment = $equipmentModel->find($equipmentId);

        if (!$equipment) {
            return redirect()->back()->with('error', 'Equipment not found.');
        }

        // Check if enough quantity available
        if ($equipment['available_quantity'] < $quantity) {
            return redirect()->back()->with('error', 'Not enough equipment available. Only ' . $equipment['available_quantity'] . ' available.');
        }

        // Create transaction record
        $transactionData = [
            'user_id' => $userId,
            'equipment_id' => $equipmentId,
            'quantity' => $quantity,
            'borrow_date' => date('Y-m-d H:i:s'),
            'returned' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($transactionModel->insert($transactionData)) {
            // Update available quantity
            $newAvailable = $equipment['available_quantity'] - $quantity;
            $equipmentModel->update($equipmentId, ['available_quantity' => $newAvailable]);

            // Send email notification
            $user = $userModel->find($userId);
            $this->sendBorrowEmail($user, $equipment, $quantity);

            return redirect()->to('assoDash_main')->with('success', 'Equipment borrowed successfully! Check your email for details.');
        } else {
            return redirect()->back()->with('error', 'Failed to process borrowing request.');
        }
    }

    public function returnEquipment()
    {
        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();
        $userModel = new UserModel();

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
        $user = $userModel->find(session()->get('user_id'));
        $this->sendReturnEmail($user, $equipment, $transaction['quantity']);

        return redirect()->to('assoDash_main')->with('success', 'Equipment returned successfully! Check your email for confirmation.');
    }

    private function sendBorrowEmail($user, $equipment, $quantity)
    {
        $emailService = \Config\Services::email();
        
        $accessories = !empty($equipment['accessories']) ? $equipment['accessories'] : 'None';
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #176734; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
                .details { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #176734; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
                strong { color: #176734; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Equipment Borrowed - SYS</h2>
                </div>
                <div class='content'>
                    <p>Dear {$user['firstname']} {$user['lastname']},</p>
                    <p>You have successfully borrowed the following equipment:</p>
                    
                    <div class='details'>
                        <p><strong>Equipment:</strong> {$equipment['name']}</p>
                        <p><strong>Category:</strong> {$equipment['category']}</p>
                        <p><strong>Quantity:</strong> {$quantity}</p>
                        <p><strong>Accessories Included:</strong> {$accessories}</p>
                        <p><strong>Borrowed Date:</strong> " . date('F d, Y h:i A') . "</p>
                    </div>
                    
                    <p><strong>Important Reminders:</strong></p>
                    <ul>
                        <li>Please take care of the equipment and its accessories</li>
                        <li>Return the equipment with ALL accessories included</li>
                        <li>Report any damages immediately to ITSO</li>
                        <li>Return the equipment on time</li>
                    </ul>
                    
                    <p>Thank you for using our equipment management system!</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from SYS - Equipment Management System</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $emailService->setTo($user['email']);
        $emailService->setSubject('Equipment Borrowed - ' . $equipment['name']);
        $emailService->setMessage($message);
        
        if (!$emailService->send()) {
            log_message('error', 'Borrow email sending failed: ' . $emailService->printDebugger(['headers']));
        }
    }

    private function sendReturnEmail($user, $equipment, $quantity)
    {
        $emailService = \Config\Services::email();
        
        $accessories = !empty($equipment['accessories']) ? $equipment['accessories'] : 'None';
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #28a745; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
                .details { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #28a745; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
                strong { color: #28a745; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Equipment Returned - SYS</h2>
                </div>
                <div class='content'>
                    <p>Dear {$user['firstname']} {$user['lastname']},</p>
                    <p>Thank you for returning the following equipment:</p>
                    
                    <div class='details'>
                        <p><strong>Equipment:</strong> {$equipment['name']}</p>
                        <p><strong>Category:</strong> {$equipment['category']}</p>
                        <p><strong>Quantity:</strong> {$quantity}</p>
                        <p><strong>Accessories:</strong> {$accessories}</p>
                        <p><strong>Returned Date:</strong> " . date('F d, Y h:i A') . "</p>
                    </div>
                    
                    <p>Your equipment has been successfully returned to the system.</p>
                    <p>Thank you for using our equipment management system responsibly!</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from SYS - Equipment Management System</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $emailService->setTo($user['email']);
        $emailService->setSubject('Equipment Returned - ' . $equipment['name']);
        $emailService->setMessage($message);
        
        if (!$emailService->send()) {
            log_message('error', 'Return email sending failed: ' . $emailService->printDebugger(['headers']));
        }
    }

    public function reservation()
    {
        $equipmentModel = new EquipmentModel();
        $reservationModel = new ReservationModel();
        $userId = session()->get('user_id');

        $data = [
            'title' => 'Reservation',
            'user' => [
                'name' => session()->get('firstname') . ' ' . session()->get('lastname'),
                'role' => session()->get('role')
            ],
            'availableEquipment' => $equipmentModel->where('is_active', 1)->findAll(),
            'upcomingReservations' => $reservationModel->getUpcomingReservations($userId),
            'pendingCount' => $reservationModel->countByStatus($userId, 'pending'),
            'confirmedCount' => $reservationModel->countByStatus($userId, 'confirmed')
        ];
        return view('assoDash_reservation', $data);
    }

    public function createReservation()
    {
        $reservationModel = new ReservationModel();
        $equipmentModel = new EquipmentModel();

        $rules = [
            'equipment_id' => 'required|numeric',
            'quantity' => 'required|numeric|greater_than[0]',
            'pickup_date' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please fill all required fields.')->withInput();
        }

        $equipmentId = $this->request->getPost('equipment_id');
        $quantity = (int)$this->request->getPost('quantity');
        $pickupDate = $this->request->getPost('pickup_date');
        $notes = $this->request->getPost('notes');
        $userId = session()->get('user_id');

        // Validate pickup date is at least 1 day in advance
        $pickupTimestamp = strtotime($pickupDate);
        $minDate = strtotime('+1 day');

        if ($pickupTimestamp < $minDate) {
            return redirect()->back()->with('error', 'Reservation must be at least 1 day in advance.')->withInput();
        }

        // Check equipment availability
        if (!$reservationModel->checkAvailability($equipmentId, $pickupDate, $quantity)) {
            return redirect()->back()->with('error', 'Equipment not available for the selected date and quantity.')->withInput();
        }

        // Create reservation
        $reservationData = [
            'user_id' => $userId,
            'equipment_id' => $equipmentId,
            'quantity' => $quantity,
            'reservation_date' => date('Y-m-d H:i:s'),
            'pickup_date' => $pickupDate,
            'status' => 'pending',
            'notes' => $notes
        ];

        if ($reservationModel->insert($reservationData)) {
            // Send reservation email
            $equipment = $equipmentModel->find($equipmentId);
            $this->sendReservationEmail($equipment, $quantity, $pickupDate);

            return redirect()->to('associate/reservation')->with('success', 'Reservation created successfully! Check your email for details.');
        } else {
            return redirect()->back()->with('error', 'Failed to create reservation.');
        }
    }

    public function cancelReservation()
    {
        $reservationModel = new ReservationModel();
        $reservationId = $this->request->getPost('reservation_id');
        $userId = session()->get('user_id');

        if ($reservationModel->cancelReservation($reservationId, $userId)) {
            return redirect()->to('associate/reservation')->with('success', 'Reservation cancelled successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to cancel reservation.');
        }
    }

    public function rescheduleReservation()
    {
        $reservationModel = new ReservationModel();
        $reservationId = $this->request->getPost('reservation_id');
        $newPickupDate = $this->request->getPost('new_pickup_date');
        $userId = session()->get('user_id');

        // Validate new pickup date is at least 1 day in advance
        $pickupTimestamp = strtotime($newPickupDate);
        $minDate = strtotime('+1 day');

        if ($pickupTimestamp < $minDate) {
            return redirect()->back()->with('error', 'New date must be at least 1 day in advance.');
        }

        // Get reservation details
        $reservation = $reservationModel->find($reservationId);

        if (!$reservation || $reservation['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Reservation not found.');
        }

        // Check availability for new date
        if (!$reservationModel->checkAvailability($reservation['equipment_id'], $newPickupDate, $reservation['quantity'], $reservationId)) {
            return redirect()->back()->with('error', 'Equipment not available for the new date.');
        }

        if ($reservationModel->rescheduleReservation($reservationId, $userId, $newPickupDate)) {
            return redirect()->to('associate/reservation')->with('success', 'Reservation rescheduled successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to reschedule reservation.');
        }
    }

    private function sendReservationEmail($equipment, $quantity, $pickupDate)
    {
        $emailService = \Config\Services::email();
        
        $userName = session()->get('firstname') . ' ' . session()->get('lastname');
        $userEmail = session()->get('email');
        $accessories = !empty($equipment['accessories']) ? $equipment['accessories'] : 'None';
        $formattedDate = date('F d, Y h:i A', strtotime($pickupDate));
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #176734; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
                .details { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #176734; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
                strong { color: #176734; }
                .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Equipment Reservation Confirmed</h2>
                </div>
                <div class='content'>
                    <p>Dear {$userName},</p>
                    <p>Your equipment reservation has been created successfully!</p>
                    
                    <div class='details'>
                        <h3 style='color: #176734; margin-top: 0;'>Reservation Details</h3>
                        <p><strong>Equipment:</strong> {$equipment['name']}</p>
                        <p><strong>Category:</strong> {$equipment['category']}</p>
                        <p><strong>Quantity:</strong> {$quantity}</p>
                        <p><strong>Accessories Included:</strong> {$accessories}</p>
                        <p><strong>Pickup Date:</strong> {$formattedDate}</p>
                        <p><strong>Status:</strong> Pending Confirmation</p>
                    </div>
                    
                    <div class='warning'>
                        <p><strong>⚠️ Important:</strong></p>
                        <ul>
                            <li>Please pick up the equipment on the scheduled date</li>
                            <li>Bring this email or your ID for verification</li>
                            <li>To cancel or reschedule, visit your dashboard</li>
                        </ul>
                    </div>
                    
                    <p>Thank you for using our reservation system!</p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from SYS - Equipment Management System</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        $emailService->setTo($userEmail);
        $emailService->setSubject('Reservation Confirmed - ' . $equipment['name']);
        $emailService->setMessage($message);
        
        if (!$emailService->send()) {
            log_message('error', 'Reservation email sending failed: ' . $emailService->printDebugger(['headers']));
        }
    }

    public function records()
    {
        $transactionModel = new TransactionModel();
        $user_id = session()->get('user_id');
        $perPage = 15;

        $data = [
            'title' => 'Records',
            'user' => [
                'name' => session()->get('firstname') . ' ' . session()->get('lastname'),
                'role' => session()->get('role')
            ],
            'history' => $transactionModel->getUserHistoryPaginated($user_id, $perPage),
            'pager' => $transactionModel->pager
        ];
        return view('assoDash_records', $data);
    }
}