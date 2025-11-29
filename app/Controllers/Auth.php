<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\ImageHandler;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function submit()
    {
        $userModel = new UserModel();
        $imageHandler = new ImageHandler();

        // Validate form inputs
        $validation = \Config\Services::validation();

        $rules = [
            'firstName' => 'required',
            'lastName'  => 'required',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'role'      => 'required',
            'password'  => 'required|min_length[6]',
            'confPassword' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $validation->getErrors())->withInput();
        }

        // Generate verification token
        $verificationToken = bin2hex(random_bytes(32));

        // Insert data
        $data = [
            'firstname'   => $this->request->getPost('firstName'),
            'lastname'    => $this->request->getPost('lastName'),
            'email'       => $this->request->getPost('email'),
            'role'        => $this->request->getPost('role'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active'   => 1,
            'is_verified' => 0,
            'verification_token' => $verificationToken,
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        // Handle profile image upload
        $profileImage = $this->request->getFile('profile_image');
        if ($profileImage && $profileImage->isValid()) {
            $result = $imageHandler->uploadUserImage($profileImage);
            if ($result['success']) {
                $data['profile_image'] = $result['filename'];
            }
            // If upload fails, just continue without profile image
        }

        $userModel->insert($data);

        // Send verification email
        $this->sendVerificationEmail($this->request->getPost('email'), $verificationToken);

        return redirect()->to('/')->with('success', 'Account created! Please check your email to verify your account.');
    }

    public function login()
{
    $userModel = new UserModel();

    // Validate form inputs
    $rules = [
        'email'     => 'required|valid_email',
        'password'  => 'required',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
    }

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    // Find user by email
    $user = $userModel->where('email', $email)->first();

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['is_verified'] == 0) {
            return redirect()->back()->with('errors', ['Please verify your email before logging in.'])->withInput();
        }
        if ($user['is_active'] == 1) {
            // Set user session
            $session = session();
            $userData = [
                'user_id'    => $user['user_id'],
                'firstname'  => $user['firstname'],
                'lastname'   => $user['lastname'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'profile_image' => $user['profile_image'] ?? null,
                'logged_in'  => true
            ];
            $session->set($userData);

            return $this->redirectToDashboard($user['role']);
                    } else {
                        return redirect()->back()->with('errors', ['Your account is deactivated.'])->withInput();
                    }
                } else {
                    return redirect()->back()->with('errors', ['Invalid email or password.'])->withInput();
                }
            }

    private function getRedirectUrl($role)
    {
        switch ($role) {
            case 'Student':
                return '/studentDash_main';
            case 'ITSO':
                return '/itsoDash_main';
            case 'Associate':
                return '/assoDash_main';
            default:
                return '/';
        }
    }

    // ADD THIS METHOD FOR THE FINAL VERSION
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'Student':
                return redirect()->to('/studentDash_main');
            case 'ITSO':
                return redirect()->to('/itsoDash_main');
            case 'Associate':
                return redirect()->to('/assoDash_main');
            default:
                return redirect()->to('/');
        }
    }

    // ADD THIS METHOD
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }

    private function sendVerificationEmail($email, $token)
    {
        $emailService = \Config\Services::email();
        
        $verificationLink = base_url("verify/{$token}");
        
        $message = "
        <html>
        <body>
            <h2>Email Verification</h2>
            <p>Thank you for registering with SYS. Please click the link below to verify your email:</p>
            <p><a href='{$verificationLink}'>Verify Email</a></p>
            <p>Or copy this link: {$verificationLink}</p>
            <p>If you did not register, please ignore this email.</p>
        </body>
        </html>
        ";
        
        $emailService->setTo($email);
        $emailService->setSubject('Verify Your Email - SYS');
        $emailService->setMessage($message);
        
        if (!$emailService->send()) {
            log_message('error', 'Email sending failed: ' . $emailService->printDebugger(['headers']));
        }
    }

    public function verify($token)
    {
        $userModel = new UserModel();
        
        $user = $userModel->where('verification_token', $token)->first();
        
        if ($user) {
            $userModel->update($user['user_id'], [
                'is_verified' => 1,
                'verification_token' => null
            ]);
            
            return redirect()->to('/')->with('success', 'Email verified successfully! You can now login.');
        } else {
            return redirect()->to('/')->with('errors', ['Invalid verification token.']);
        }
    }

    public function forgotPassword()
    {
        return view('forgot_password');
    }

    public function sendResetLink()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            $resetToken = bin2hex(random_bytes(32));
            
            $userModel->update($user['user_id'], [
                'reset_token' => $resetToken,
                'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
            ]);

            $this->sendPasswordResetEmail($email, $resetToken);
        }

        return redirect()->to('/')->with('success', 'If your email exists, you will receive a password reset link.');
    }

    private function sendPasswordResetEmail($email, $token)
    {
        $emailService = \Config\Services::email();
        
        $resetLink = base_url("reset-password/{$token}");
        
        $message = "
        <html>
        <body>
            <h2>Password Reset Request</h2>
            <p>You requested to reset your password. Click the link below to reset it:</p>
            <p><a href='{$resetLink}'>Reset Password</a></p>
            <p>Or copy this link: {$resetLink}</p>
            <p>This link will expire in 1 hour.</p>
            <p>If you did not request this, please ignore this email.</p>
        </body>
        </html>
        ";
        
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request - SYS');
        $emailService->setMessage($message);
        
        if (!$emailService->send()) {
            log_message('error', 'Email sending failed: ' . $emailService->printDebugger(['headers']));
        }
    }

    public function resetPassword($token)
    {
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)
                          ->where('reset_token_expires >=', date('Y-m-d H:i:s'))
                          ->first();

        if (!$user) {
            return redirect()->to('/')->with('errors', ['Invalid or expired reset token.']);
        }

        return view('reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'confPassword' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $userModel = new UserModel();
        $token = $this->request->getPost('token');
        
        $user = $userModel->where('reset_token', $token)
                          ->where('reset_token_expires >=', date('Y-m-d H:i:s'))
                          ->first();

        if (!$user) {
            return redirect()->to('/')->with('errors', ['Invalid or expired reset token.']);
        }

        $userModel->update($user['user_id'], [
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_token_expires' => null
        ]);

        return redirect()->to('/')->with('success', 'Password reset successfully! You can now login.');
    }
}