<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Judges extends BaseController
{
    public function index()
    {
        // Sample data - replace with your actual data
        $data = [
            'system_name' => 'Pageant System',
            'primary_color' => '#6f42c1', // Purple
            'secondary_color' => '#495057', // Gray
            'accent_color' => '#28a745', // Green
            'judges' => [], // Empty array for now - replace with your judges data
            'message' => session()->getFlashdata('message') ?? '',
            'error' => session()->getFlashdata('error') ?? ''
        ];

        return view('admin/judges', $data);
    }
}
