<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JudgeModel;
use App\Models\UserModel;
use App\Models\EventModel;

class Judges extends BaseController
{

    public function index($eventId = null)
    {
        $judgeModel = new JudgeModel();
        // Determine event: use route param if provided, otherwise active event
        if ($eventId === null) {
            try {
                $em = new EventModel();
                $active = $em->where('is_active', 1)->orderBy('id', 'DESC')->first();
                if ($active) {
                    $eventId = (int) $active['id'];
                }
            } catch (\Throwable $e) {
                // ignore if events table missing
            }
        } else {
            $eventId = (int) $eventId;
        }

        // Join judges with users to get username
        $judges = $judgeModel
            ->select('judges.*, users.username')
            ->join('users', 'users.id = judges.user_id', 'left')
            ->where('judges.event_id', $eventId) 
            ->findAll();

        $data = [
            'primary_color'   => '#6f42c1',
            'secondary_color' => '#495057',
            'accent_color'    => '#28a745',
            'judges'          => $judges,
            'event_id'        => $eventId,          
            'active_event_id' => $eventId,          
            'message'         => session()->getFlashdata('message') ?? '',
            'error'           => session()->getFlashdata('error') ?? '',
        ];

        return view('Admin/judges', $data);
    }

    public function create($eventId = null)
    {

        if (empty($eventId)) {
        throw new \RuntimeException('Missing eventId when creating judge');
    }

        
        // Validation
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'username'   => 'required|min_length[3]|max_length[50]',
            'email'      => 'required|valid_email|max_length[255]',
            'password'   => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON([
                        'status'     => 'error',
                        'message'    => 'Please correct the errors in the form.',
                        'validation' => $this->validator->getErrors(),
                    ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please correct the errors in the form.')
                ->with('validation', $this->validator);
        }

        $userModel  = new UserModel();
        $judgeModel = new JudgeModel();

       
        $userData = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'first_name'    => $this->request->getPost('first_name'),
            'last_name'     => $this->request->getPost('last_name'),
            'role'          => 'judge',
        ];

        try {
            $userId = $userModel->insert($userData);

            if (! $userId) {
                throw new \RuntimeException('Failed to create user record for judge.');
            }

            $judgeData = [
                'user_id'          => $userId,
                'event_id'         => $eventId,
                'first_name'       => $this->request->getPost('first_name'),
                'last_name'        => $this->request->getPost('last_name'),
                'email'            => $this->request->getPost('email'),
                'phone'            => null,
                'specialization'   => null,
                'experience_years' => 0,
                'status'           => 'active',
            ];

            $judgeModel->insert($judgeData);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Judge added successfully.',
                ]);
            }

            return redirect()
                ->to("/events/{$eventId}/judges")
                ->with('message', 'Judge added successfully');
        } catch (\Throwable $e) {
            log_message('error', 'Error Creating Judge: {error}', ['error' => $e->getMessage()]);

            if ($this->request->isAJAX()) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'An error occurred while adding the judge. Please try again.',
                    ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while adding the judge. Please try again.');
        }
    }
}