<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    public function dashboard()
    {
        // Initialize session if not started
        if (!session()->has('user_name')) {
            session()->set('user_name', 'Admin User');
        }
        
        // Sample data - replace with actual database queries
        $data = [
            'system_name' => 'Pageant Management System',
            'primary_color' => '#6f42c1',
            'secondary_color' => '#495057',
            'accent_color' => '#28a745',
            'total_contestants' => 0,
            'total_judges' => 0,
            'total_rounds' => 0,
            'active_round' => null,
            'judge_progress' => [],
            'leaderboard' => [],
            'success_message' => '',
            'error_message' => '',
            'csrf_token' => csrf_hash(),
            'allow_public_voting' => false,
            'score_stats' => ['finalized_judges' => 0]
        ];
        
        return view('admin/dashboard', $data);
    }

    public function dashboardStats()
    {
        // TODO: Replace with real DB queries once models are available
        $response = [
            'success' => true,
            'total_contestants' => 0,
            'total_judges' => 0,
            'total_rounds' => 0,
            'scores_submitted' => 0,
        ];

        return $this->response->setJSON($response);
    }
}
