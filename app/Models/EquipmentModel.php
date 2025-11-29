<?php

namespace App\Models;

use CodeIgniter\Model;

class EquipmentModel extends Model
{
    protected $table = 'equipment';
    protected $primaryKey = 'equipment_id';
    protected $allowedFields = [
        'name',
        'description',
        'category',
        'total_quantity',
        'available_quantity',
        'is_active',
        'accessories',
        'equipment_image',
        'created_at'
    ];

    /**
     * Delete equipment image and thumbnail files
     */
    public function deleteEquipmentImages($filename)
    {
        if (empty($filename)) return;

        $uploadPath = WRITEPATH . 'uploads/equipment/';
        $thumbPath = WRITEPATH . 'uploads/thumbnails/equipment/';

        if (file_exists($uploadPath . $filename)) {
            unlink($uploadPath . $filename);
        }
        if (file_exists($thumbPath . $filename)) {
            unlink($thumbPath . $filename);
        }
    }

    // Get only items with available quantity
    public function getAvailableItems()
    {
        return $this->select('equipment.equipment_id, equipment.name, equipment.available_quantity')
            ->where('equipment.available_quantity >', 0)
            ->where('equipment.is_active', 1)
            ->findAll();
    }

    // Get all equipment grouped by category
    public function getEquipmentByCategory()
    {
        return $this->select('category, COUNT(*) as count, SUM(total_quantity) as total_qty')
            ->where('is_active', 1)
            ->groupBy('category')
            ->findAll();
    }

    // Get equipment by category
    public function getEquipmentInCategory($category)
    {
        return $this->where('category', $category)
            ->where('is_active', 1)
            ->findAll();
    }

    // Get all active equipment
    public function getAllActive()
    {
        return $this->where('is_active', 1)
            ->orderBy('category', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}
