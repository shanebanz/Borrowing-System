<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'About Us',
            'user' => [
                'name' => session()->get('firstname') . ' ' . session()->get('lastname'),
                'role' => session()->get('role')
            ]
        ];
        
        return view('about', $data);
    }
}
