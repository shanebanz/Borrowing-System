<?php

namespace App\Libraries;

class ImageHandler
{
    protected $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    protected $maxSize = 2048; // 2MB in KB
    protected $thumbWidth = 150;
    protected $thumbHeight = 150;

    /**
     * Validate uploaded image file
     */
    public function validateImage($file)
    {
        $errors = [];

        if (!$file->isValid()) {
            $errors[] = 'Invalid file upload';
            return $errors;
        }

        // Check file type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedTypes)) {
            $errors[] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        }

        // Check file size
        $fileSize = $file->getSize() / 1024; // Convert to KB
        if ($fileSize > $this->maxSize) {
            $errors[] = 'File size exceeds ' . ($this->maxSize / 1024) . 'MB limit.';
        }

        return $errors;
    }

    /**
     * Upload and create thumbnail for user profile image
     */
    public function uploadUserImage($file)
    {
        $errors = $this->validateImage($file);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $uploadPath = WRITEPATH . 'uploads/profiles/';
        $thumbPath = WRITEPATH . 'uploads/thumbnails/profiles/';

        // Generate unique filename
        $newName = $file->getRandomName();

        // Move original file
        if (!$file->move($uploadPath, $newName)) {
            return ['success' => false, 'errors' => ['Failed to upload image']];
        }

        // Create thumbnail
        $this->createThumbnail($uploadPath . $newName, $thumbPath . $newName);

        return ['success' => true, 'filename' => $newName];
    }

    /**
     * Upload and create thumbnail for equipment image
     */
    public function uploadEquipmentImage($file)
    {
        $errors = $this->validateImage($file);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $uploadPath = WRITEPATH . 'uploads/equipment/';
        $thumbPath = WRITEPATH . 'uploads/thumbnails/equipment/';

        // Generate unique filename
        $newName = $file->getRandomName();

        // Move original file
        if (!$file->move($uploadPath, $newName)) {
            return ['success' => false, 'errors' => ['Failed to upload image']];
        }

        // Create thumbnail
        $this->createThumbnail($uploadPath . $newName, $thumbPath . $newName);

        return ['success' => true, 'filename' => $newName];
    }

    /**
     * Create thumbnail from image
     */
    protected function createThumbnail($sourcePath, $destPath)
    {
        $image = \Config\Services::image('gd');

        try {
            $image->withFile($sourcePath)
                ->fit($this->thumbWidth, $this->thumbHeight, 'center')
                ->save($destPath);
        } catch (\Exception $e) {
            log_message('error', 'Thumbnail creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Get image URL for display
     */
    public function getUserImageUrl($filename, $thumbnail = false)
    {
        if (empty($filename)) {
            return base_url('assets/images/default-avatar.png');
        }

        $path = $thumbnail ? 'uploads/thumbnails/profiles/' : 'uploads/profiles/';
        $fullPath = WRITEPATH . $path . $filename;

        if (file_exists($fullPath)) {
            return base_url('writable/' . $path . $filename);
        }

        return base_url('assets/images/default-avatar.png');
    }

    /**
     * Get equipment image URL for display
     */
    public function getEquipmentImageUrl($filename, $thumbnail = false)
    {
        if (empty($filename)) {
            return base_url('assets/images/default-equipment.png');
        }

        $path = $thumbnail ? 'uploads/thumbnails/equipment/' : 'uploads/equipment/';
        $fullPath = WRITEPATH . $path . $filename;

        if (file_exists($fullPath)) {
            return base_url('writable/' . $path . $filename);
        }

        return base_url('assets/images/default-equipment.png');
    }
}
