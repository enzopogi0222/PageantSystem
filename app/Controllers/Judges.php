<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Judges extends BaseController
{
    public function index()
    {
        // Sample data - replace with your actual data
        $data = [
            'primary_color' => '#6f42c1', // Purple
            'secondary_color' => '#495057', // Gray
            'accent_color' => '#28a745', // Green
            // Demo data so the table renders; replace with real DB results later
            'judges' => [
                [
                    'id' => 1,
                    'first_name' => 'Alice',
                    'last_name'  => 'Santos',
                    'username'   => 'alice',
                    'email'      => 'alice@example.com',
                    'status'     => 'active',
                    'created_at' => date('Y-m-d', strtotime('-7 days')),
                ],
                [
                    'id' => 2,
                    'first_name' => 'Brian',
                    'last_name'  => 'Reyes',
                    'username'   => 'brian',
                    'email'      => 'brian@example.com',
                    'status'     => 'inactive',
                    'created_at' => date('Y-m-d', strtotime('-3 days')),
                ],
                [
                    'id' => 3,
                    'first_name' => 'Carla',
                    'last_name'  => 'Lopez',
                    'username'   => 'carla',
                    'email'      => 'carla@example.com',
                    'status'     => 'active',
                    'created_at' => date('Y-m-d'),
                ],
            ],
            'message' => session()->getFlashdata('message') ?? '',
            'error' => session()->getFlashdata('error') ?? ''
        ];

        try {
            return view('Admin/judges', $data);
        } catch (\Throwable $e) {
            return 'View error in admin/judges: ' . $e->getMessage();
        }
    }
}
