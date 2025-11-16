<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\JudgeModel;
use App\Models\EventModel;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if ($session->get('role') === 'judge') {
            $userId = $session->get('user_id');

            if (!$userId) {
                $session->remove(['user_id', 'username', 'role', 'isLoggedIn']);
                return redirect()->to('/login');
            }

            $judgeModel = new JudgeModel();
            $eventModel = new EventModel();

            $judge = $judgeModel
                ->where('user_id', $userId)
                ->get(1)
                ->getRowArray();

            if (!$judge || !isset($judge['event_id'])) {
                $session->setFlashdata('error', 'Your judge account is not linked to an event. Please contact the administrator.');
                $session->remove(['user_id', 'username', 'role', 'isLoggedIn']);
                return redirect()->to('/login');
            }

            $event = $eventModel->find($judge['event_id']);

            if (!$event || empty($event['is_active'])) {
                $session->setFlashdata('error', 'The event assigned to your judge account is not active. You have been logged out.');
                $session->remove(['user_id', 'username', 'role', 'isLoggedIn']);
                return redirect()->to('/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action after
    }
}
