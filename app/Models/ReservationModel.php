<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'reservation_id';
    protected $allowedFields = [
        'user_id',
        'equipment_id',
        'quantity',
        'reservation_date',
        'pickup_date',
        'return_date',
        'status',
        'notes',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all reservations for a specific user
     */
    public function getUserReservations($userId, $status = null)
    {
        $builder = $this->db->table($this->table)
            ->select('reservations.*, equipment.name as equipment_name, equipment.category, equipment.accessories')
            ->join('equipment', 'equipment.equipment_id = reservations.equipment_id')
            ->where('reservations.user_id', $userId)
            ->orderBy('reservations.pickup_date', 'DESC');

        if ($status) {
            $builder->where('reservations.status', $status);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get upcoming reservations for a user
     */
    public function getUpcomingReservations($userId)
    {
        return $this->db->table($this->table)
            ->select('reservations.*, equipment.name as equipment_name, equipment.category, equipment.accessories')
            ->join('equipment', 'equipment.equipment_id = reservations.equipment_id')
            ->where('reservations.user_id', $userId)
            ->whereIn('reservations.status', ['pending', 'confirmed'])
            ->where('reservations.pickup_date >', date('Y-m-d H:i:s'))
            ->orderBy('reservations.pickup_date', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Check if equipment is available for reservation on a specific date
     */
    public function checkAvailability($equipmentId, $pickupDate, $quantity, $excludeReservationId = null)
    {
        $equipmentModel = new \App\Models\EquipmentModel();
        $equipment = $equipmentModel->find($equipmentId);

        if (!$equipment) {
            return false;
        }

        // Get total reserved quantity for the pickup date
        $builder = $this->db->table($this->table)
            ->selectSum('quantity')
            ->where('equipment_id', $equipmentId)
            ->where('pickup_date <=', $pickupDate)
            ->whereIn('status', ['pending', 'confirmed'])
            ->groupStart()
                ->where('return_date IS NULL')
                ->orWhere('return_date >=', $pickupDate)
            ->groupEnd();

        if ($excludeReservationId) {
            $builder->where('reservation_id !=', $excludeReservationId);
        }

        $result = $builder->get()->getRowArray();
        $reservedQuantity = $result['quantity'] ?? 0;

        $availableQuantity = $equipment['total_quantity'] - $reservedQuantity;

        return $availableQuantity >= $quantity;
    }

    /**
     * Count reservations by status for a user
     */
    public function countByStatus($userId, $status)
    {
        return $this->where('user_id', $userId)
            ->where('status', $status)
            ->countAllResults();
    }

    /**
     * Cancel a reservation
     */
    public function cancelReservation($reservationId, $userId)
    {
        return $this->where('reservation_id', $reservationId)
            ->where('user_id', $userId)
            ->set(['status' => 'cancelled'])
            ->update();
    }

    /**
     * Reschedule a reservation
     */
    public function rescheduleReservation($reservationId, $userId, $newPickupDate)
    {
        return $this->where('reservation_id', $reservationId)
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->set(['pickup_date' => $newPickupDate])
            ->update();
    }
}
