<?php

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        $session = \Config\Services::session();
        return $session->get('logged_in') === true;
    }
}

if (!function_exists('check_student_access')) {
    function check_student_access()
    {
        $session = \Config\Services::session();
        if (!is_logged_in() || $session->get('role') !== 'Student') {
            return redirect()->to('/')->with('errors', ['Access denied. Student role required.']);
        }
    }
}

if (!function_exists('check_it_access')) {
    function check_it_access()
    {
        $session = \Config\Services::session();
        if (!is_logged_in() || $session->get('role') !== 'ITSO') { // CHANGED
            return redirect()->to('/')->with('errors', ['Access denied. ITSO role required.']);
        }
    }
}

if (!function_exists('check_associate_access')) {
    function check_associate_access()
    {
        $session = \Config\Services::session();
        if (!is_logged_in() || $session->get('role') !== 'Associate') { // Changed from 'Associates' to 'Associate'
            return redirect()->to('/')->with('errors', ['Access denied. Associates role required.']);
        }
    }
}

if (!function_exists('get_profile_image_url')) {
    function get_profile_image_url()
    {
        $session = \Config\Services::session();
        $profileImage = $session->get('profile_image');
        
        if (!empty($profileImage) && file_exists(WRITEPATH . 'uploads/thumbnails/profiles/' . $profileImage)) {
            return base_url('media/thumbnails/profiles/' . $profileImage);
        }
        
        return base_url('assets/images/default-avatar.png');
    }
}