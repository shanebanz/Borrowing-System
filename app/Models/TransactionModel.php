<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'borrow_transactions';
    protected $primaryKey = 'borrow_id';
    protected $allowedFields = ['user_id', 'equipment_id', 'quantity', 'borrow_date', 'return_date', 'returned', 'created_at'];

    // Get active borrowed equipment for a user
    public function getActiveBorrowedItems($user_id)
    {
        return $this->select('borrow_transactions.*, equipment.name, equipment.category, equipment.accessories')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->where('borrow_transactions.user_id', $user_id)
            ->where('borrow_transactions.returned', 0)
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->findAll();
    }

    // Get paginated active borrowed equipment for a user
    public function getActiveBorrowedItemsPaginated($user_id, $perPage = 8)
    {
        return $this->select('borrow_transactions.*, equipment.name, equipment.category, equipment.accessories')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->where('borrow_transactions.user_id', $user_id)
            ->where('borrow_transactions.returned', 0)
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->paginate($perPage);
    }

    // Get all borrowed equipment with user details
    public function getAllBorrowedWithUsers()
    {
        return $this->select('borrow_transactions.*, equipment.name as equipment_name, equipment.category, equipment.accessories, users.firstname, users.lastname, users.email')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->join('users', 'users.user_id = borrow_transactions.user_id')
            ->where('borrow_transactions.returned', 0)
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->findAll();
    }

    // Get paginated borrowed equipment with user details
    public function getAllBorrowedWithUsersPaginated($perPage = 8)
    {
        return $this->select('borrow_transactions.*, equipment.name as equipment_name, equipment.category, equipment.accessories, users.firstname, users.lastname, users.email')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->join('users', 'users.user_id = borrow_transactions.user_id')
            ->where('borrow_transactions.returned', 0)
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->paginate($perPage);
    }

    public function countPending($user_id)
    {
        return $this->where('user_id', $user_id)->where('returned', 0)->countAllResults();
    }

    public function countReturned($user_id)
    {
        return $this->where('user_id', $user_id)->where('returned', 1)->countAllResults();
    }

    // Get borrowing history for a user
    public function getUserHistory($user_id)
    {
        return $this->select('borrow_transactions.*, equipment.name, equipment.category, equipment.accessories')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->where('borrow_transactions.user_id', $user_id)
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->findAll();
    }

    // Get all borrowing history with user and equipment details for reports
    public function getAllBorrowingHistory()
    {
        return $this->db->table($this->table)
            ->select('borrow_transactions.*, users.firstname as user_firstname, users.lastname as user_lastname, users.email as user_email, equipment.name as equipment_name, equipment.category')
            ->join('users', 'users.user_id = borrow_transactions.user_id')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get paginated borrowing history for reports
    public function getAllBorrowingHistoryPaginated($perPage = 8)
    {
        return $this->select('borrow_transactions.*, users.firstname as user_firstname, users.lastname as user_lastname, users.email as user_email, equipment.name as equipment_name, equipment.category')
            ->join('users', 'users.user_id = borrow_transactions.user_id')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->paginate($perPage);
    }

    // Get paginated user history
    public function getUserHistoryPaginated($user_id, $perPage = 8)
    {
        return $this->select('borrow_transactions.*, equipment.name, equipment.category, equipment.accessories')
            ->join('equipment', 'equipment.equipment_id = borrow_transactions.equipment_id')
            ->where('borrow_transactions.user_id', $user_id)
            ->orderBy('borrow_transactions.borrow_date', 'DESC')
            ->paginate($perPage);
    }
}
