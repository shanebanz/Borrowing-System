<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\EquipmentModel;
use App\Models\TransactionModel;
use App\Libraries\ImageHandler;

class It extends BaseController
{
    public function __construct()
    {
        helper('auth');
        check_it_access();
    }

    public function dashboard()
    {
        $data = [
            'title' => 'ITSO Dashboard',
            'user' => [
                'name' => session()->get('firstname') . ' ' . session()->get('lastname'),
                'role' => session()->get('role')
            ]
        ];
        return view('itsoDash_main', $data); 
    }

    public function items()
    {
        $equipmentModel = new EquipmentModel();
        $perPage = 8;
        
        $data = [
            'title' => 'ITSO Items',
            'equipment' => $equipmentModel->where('is_active', 1)->orderBy('category', 'ASC')->orderBy('name', 'ASC')->paginate($perPage),
            'pager' => $equipmentModel->pager
        ];
        return view('itso_items', $data);
    }

    public function records()
    {
        $transactionModel = new TransactionModel();
        $perPage = 8;

        $data = [
            'title' => 'ITSO Records',
            'user' => [
                'name' => session()->get('firstname') . ' ' . session()->get('lastname'),
                'role' => session()->get('role')
            ],
            'activeTransactions' => $transactionModel->getAllBorrowedWithUsersPaginated($perPage),
            'pager' => $transactionModel->pager
        ];
        return view('itso_records', $data);
    }

    // User Management Methods
    public function users()
    {
        $userModel = new UserModel();
        $perPage = 8;
        
        $data = [
            'title' => 'User Management',
            'users' => $userModel->orderBy('user_id', 'DESC')->paginate($perPage),
            'pager' => $userModel->pager
        ];
        return view('itso_users', $data);
    }

    public function getUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if ($user) {
            return $this->response->setJSON([
                'success' => true,
                'user' => $user
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
    }

    public function updateUser()
    {
        $userModel = new UserModel();
        $imageHandler = new ImageHandler();
        
        $rules = [
            'user_id' => 'required|numeric',
            'firstname' => 'required|min_length[2]|max_length[50]',
            'lastname' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email',
            'role' => 'required|in_list[Student,Associate,ITSO]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please fill all fields correctly.')->withInput();
        }

        $userId = $this->request->getPost('user_id');
        $email = $this->request->getPost('email');

        // Check if email is already used by another user
        $existingUser = $userModel->where('email', $email)
                                   ->where('user_id !=', $userId)
                                   ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Email already exists for another user.')->withInput();
        }

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $email,
            'role' => $this->request->getPost('role')
        ];

        // Handle profile image upload
        $profileImage = $this->request->getFile('profile_image');
        if ($profileImage && $profileImage->isValid()) {
            $result = $imageHandler->uploadUserImage($profileImage);
            if ($result['success']) {
                // Delete old image if exists
                $user = $userModel->find($userId);
                if ($user && !empty($user['profile_image'])) {
                    $userModel->deleteUserImages($user['profile_image']);
                }
                $data['profile_image'] = $result['filename'];
            } else {
                return redirect()->back()->with('error', implode(', ', $result['errors']))->withInput();
            }
        }

        if ($userModel->update($userId, $data)) {
            return redirect()->to('itso/users')->with('success', 'User updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update user.')->withInput();
        }
    }

    public function deactivateUser()
    {
        $userModel = new UserModel();
        
        $userId = $this->request->getPost('user_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Invalid user ID.');
        }

        // Prevent deactivating self
        if ($userId == session()->get('user_id')) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($userModel->update($userId, ['is_active' => 0])) {
            return redirect()->to('itso/users')->with('success', 'User deactivated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to deactivate user.');
        }
    }

    public function activateUser()
    {
        $userModel = new UserModel();
        
        $userId = $this->request->getPost('user_id');

        if (!$userId) {
            return redirect()->back()->with('error', 'Invalid user ID.');
        }

        if ($userModel->update($userId, ['is_active' => 1])) {
            return redirect()->to('itso/users')->with('success', 'User activated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to activate user.');
        }
    }

    // Equipment Management Methods
    public function getEquipment($id)
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

    public function addEquipment()
    {
        $equipmentModel = new EquipmentModel();
        $imageHandler = new ImageHandler();
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'category' => 'required',
            'total_quantity' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please fill all required fields correctly.')->withInput();
        }

        $totalQty = $this->request->getPost('total_quantity');
        $accessories = $this->request->getPost('accessories');

        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'total_quantity' => $totalQty,
            'available_quantity' => $totalQty, // Initially all available
            'accessories' => $accessories,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Handle equipment image upload
        $equipmentImage = $this->request->getFile('equipment_image');
        if ($equipmentImage && $equipmentImage->isValid()) {
            $result = $imageHandler->uploadEquipmentImage($equipmentImage);
            if ($result['success']) {
                $data['equipment_image'] = $result['filename'];
            } else {
                return redirect()->back()->with('error', implode(', ', $result['errors']))->withInput();
            }
        }

        if ($equipmentModel->insert($data)) {
            return redirect()->to('itso/items')->with('success', 'Equipment added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add equipment.')->withInput();
        }
    }

    public function updateEquipment()
    {
        $equipmentModel = new EquipmentModel();
        $imageHandler = new ImageHandler();
        
        $rules = [
            'equipment_id' => 'required|numeric',
            'name' => 'required|min_length[2]|max_length[100]',
            'category' => 'required',
            'total_quantity' => 'required|numeric|greater_than[0]',
            'available_quantity' => 'required|numeric|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Please fill all fields correctly.')->withInput();
        }

        $equipmentId = $this->request->getPost('equipment_id');
        $totalQty = $this->request->getPost('total_quantity');
        $availableQty = $this->request->getPost('available_quantity');

        // Validate available quantity doesn't exceed total
        if ($availableQty > $totalQty) {
            return redirect()->back()->with('error', 'Available quantity cannot exceed total quantity.')->withInput();
        }

        $accessories = $this->request->getPost('accessories');

        $data = [
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'total_quantity' => $totalQty,
            'available_quantity' => $availableQty,
            'accessories' => $accessories
        ];

        // Handle equipment image upload
        $equipmentImage = $this->request->getFile('equipment_image');
        if ($equipmentImage && $equipmentImage->isValid()) {
            $result = $imageHandler->uploadEquipmentImage($equipmentImage);
            if ($result['success']) {
                // Delete old image if exists
                $equipment = $equipmentModel->find($equipmentId);
                if ($equipment && !empty($equipment['equipment_image'])) {
                    $equipmentModel->deleteEquipmentImages($equipment['equipment_image']);
                }
                $data['equipment_image'] = $result['filename'];
            } else {
                return redirect()->back()->with('error', implode(', ', $result['errors']))->withInput();
            }
        }

        if ($equipmentModel->update($equipmentId, $data)) {
            return redirect()->to('itso/items')->with('success', 'Equipment updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update equipment.')->withInput();
        }
    }

    public function deactivateEquipment()
    {
        $equipmentModel = new EquipmentModel();
        
        $equipmentId = $this->request->getPost('equipment_id');

        if (!$equipmentId) {
            return redirect()->back()->with('error', 'Invalid equipment ID.');
        }

        if ($equipmentModel->update($equipmentId, ['is_active' => 0])) {
            return redirect()->to('itso/items')->with('success', 'Equipment deactivated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to deactivate equipment.');
        }
    }

    // Reports Methods
    public function reports()
    {
        $equipmentModel = new EquipmentModel();
        $transactionModel = new TransactionModel();
        $perPage = 8;

        $reportType = $this->request->getGet('type') ?? 'active';
        
        $data = [
            'title' => 'Reports',
            'reportType' => $reportType
        ];

        if ($reportType === 'active') {
            $data['activeEquipment'] = $equipmentModel->where('is_active', 1)->paginate($perPage);
            $data['pager'] = $equipmentModel->pager;
        } elseif ($reportType === 'unusable') {
            $data['unusableEquipment'] = $equipmentModel->where('is_active', 0)->paginate($perPage);
            $data['pager'] = $equipmentModel->pager;
        } else {
            $data['borrowingHistory'] = $transactionModel->getAllBorrowingHistoryPaginated($perPage);
            $data['pager'] = $transactionModel->pager;
        }

        return view('itso_reports', $data);
    }
}