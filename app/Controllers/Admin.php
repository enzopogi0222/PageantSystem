<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EventModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        // Initialize session if not started
        if (!session()->has('user_name')) {
            session()->set('user_name', 'Admin User');
        }
        $session = session();
        $success = $session->getFlashdata('success') ?? '';
        
        // Get active event name; if none, use the latest by ID
        $latestEventName = null;
        try {
            $eventModel = new EventModel();
            $active = $eventModel->where('is_active', 1)->orderBy('id', 'DESC')->first();
            if ($active && !empty($active['name'])) {
                $latestEventName = $active['name'];
            } else {
                $latest = $eventModel->orderBy('id', 'DESC')->first();
                if ($latest) {
                    $latestEventName = $latest['name'] ?? null;
                }
            }
        } catch (\Throwable $e) {
            // ignore if table not migrated yet
        }
        
        // Sample data - replace with actual database queries
        $data = [
            'system_name' => $latestEventName ?: 'Pageant Management System',
            'primary_color' => '#6f42c1',
            'secondary_color' => '#495057',
            'accent_color' => '#28a745',
            'total_contestants' => 0,
            'total_judges' => 0,
            'total_rounds' => 0,
            'active_round' => null,
            'judge_progress' => [],
            'leaderboard' => [],
            'success_message' => $success,
            'error_message' => '',
            'csrf_token' => csrf_hash(),
            'allow_public_voting' => false,
            'score_stats' => ['finalized_judges' => 0],
            'latest_event_name' => $latestEventName,
        ];
        
        return view('admin/dashboard', $data);
    }

    public function adminHome()
    {
        // Try to use latest event name for header title
        $latestEventName = null;
        $latestEvent = null;
        $allEvents = [];
        try {
            $eventModel = new EventModel();
            $latest = $eventModel->orderBy('id', 'DESC')->first();
            if ($latest) {
                $latestEventName = $latest['name'] ?? null;
                $latestEvent = $latest;
            }
            // Fetch all events (newest first) for listing
            $allEvents = $eventModel->orderBy('id', 'DESC')->findAll();
        } catch (\Throwable $e) {
            // ignore if table not migrated yet
        }

        $data = [
            'username' => session('username') ?? 'Admin',
            'page_title' => 'Admin',
            // Home should display the system name (not the latest event)
            'system_name' => 'Pageant Management System',
            'user_greeting' => 'Hello, ' . (session('username') ?? 'Admin'),
            'logout_url' => base_url('logout'),
            'hide_center_nav' => true,
            'latest_event' => $latestEvent,
            'events' => $allEvents,
        ];
        return view('Admin/min_admin', $data);
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
