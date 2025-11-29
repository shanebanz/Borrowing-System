<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowBeforeDelete = ['beforeDelete'];

    protected $allowedFields = [
        'firstname',
        'lastname',
        'email',
        'password_hash',
        'role',
        'is_active',
        'is_verified',
        'verification_token',
        'reset_token',
        'reset_token_expires',
        'profile_image',
        'created_at'
    ];

    /**
     * Delete user profile image and thumbnail before deleting user
     */
    protected function beforeDelete(array $data)
    {
        if (isset($data['id']) && is_array($data['id'])) {
            foreach ($data['id'] as $userId) {
                $user = $this->find($userId);
                if ($user && !empty($user['profile_image'])) {
                    $this->deleteUserImages($user['profile_image']);
                }
            }
        }
        return $data;
    }

    /**
     * Delete user profile image and thumbnail files
     */
    public function deleteUserImages($filename)
    {
        $uploadPath = WRITEPATH . 'uploads/profiles/';
        $thumbPath = WRITEPATH . 'uploads/thumbnails/profiles/';

        if (file_exists($uploadPath . $filename)) {
            unlink($uploadPath . $filename);
        }
        if (file_exists($thumbPath . $filename)) {
            unlink($thumbPath . $filename);
        }
    }
}
