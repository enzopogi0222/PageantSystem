<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Contestant extends BaseController
{
    public function index()
    {
        // Sample data - replace with actual database queries
        $data = [
            'primary_color' => '#6f42c1',
            'secondary_color' => '#495057',
            'accent_color' => '#28a745',
            'contestants' => [], // Empty array for now
            'success_message' => '',
            'error_message' => '',
            'csrf_token' => csrf_hash()
        ];
        
        return view('Admin/contestants', $data);
    }
}
