<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EventModel;

class Results extends BaseController
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

        $data = [
            'system_name'      => $latestEventName ?: 'Pageant Management System',
            'active_event_id'  => $activeEventId,
        ];

        return view('admin/results', $data);
    }
}
