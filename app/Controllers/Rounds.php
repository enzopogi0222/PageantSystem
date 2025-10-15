<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Rounds extends BaseController
{
    public function index()
    {
         $data = [
            'system_name' => 'Pageant System',
            'primary_color' => '#6f42c1',
            'secondary_color' => '#495057',
            'accent_color' => '#28a745',
            'rounds_with_criteria' => [], 
            'message' => session()->getFlashdata('message') ?? '',
            'error' => session()->getFlashdata('error') ?? '',
            'current_page' => 'rounds'
        ];

        return view ('admin/rounds', $data);
    }
}
