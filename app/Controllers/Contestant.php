<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EventModel;

class Contestant extends BaseController
{
    public function index()
    {
       
        $latestEventName = null;
        $activeEventId   = null;

        try {
            $eventModel = new EventModel();

            $active = $eventModel->where('is_active', 1)
                                  ->orderBy('id', 'DESC')
                                  ->first();

            if ($active && !empty($active['name'])) {
                $latestEventName = $active['name'];
                $activeEventId   = $active['id'];
            } else {
                $latest = $eventModel->orderBy('id', 'DESC')->first();
                if ($latest) {
                    $latestEventName = $latest['name'] ?? null;
                    $activeEventId   = $latest['id'] ?? null;
                }
            }
        } catch (\Throwable $e) {
            
        }

        // Sample data - replace with actual database queries
        $data = [
            'system_name'      => $latestEventName ?: 'Pageant Management System',
            'active_event_id'  => $activeEventId,
            'primary_color'    => '#6f42c1',
            'secondary_color'  => '#495057',
            'accent_color'     => '#28a745',
            'contestants'      => [], 
            'success_message'  => '',
            'error_message'    => '',
            'csrf_token'       => csrf_hash(),
        ];
        
        return view('Admin/contestants', $data);
    }
}
