<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\EventModel;
use App\Models\ContestantModel;

class Contestant extends BaseController
{
    public function index($eventId)
    {
        $eventModel      = new EventModel();
        $contestantModel = new ContestantModel();

        $event = $eventModel->find($eventId);

        if (! $event) {
            throw new \RuntimeException('Event not found for contestants');
        }

        $contestants = $contestantModel
            ->where('event_id', $eventId)
            ->orderBy('contestant_number', 'ASC')
            ->findAll();

        $data = [
            'system_name'      => $event['name'] ?? 'Pageant Management System',
            'active_event_id'  => $eventId,
            'primary_color'    => '#6f42c1',
            'secondary_color'  => '#495057',
            'accent_color'     => '#28a745',
            'contestants'      => $contestants,
            'success_message'  => session()->getFlashdata('message') ?? '',
            'error_message'    => session()->getFlashdata('error') ?? '',
            'csrf_token'       => csrf_hash(),
        ];

        return view('Admin/contestants', $data);
    }

    public function create($eventId = null)
    {
        if (empty($eventId)) {
            throw new \RuntimeException('Missing eventId when creating contestant');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'email'      => 'required|valid_email|max_length[255]',
            'phone'      => 'permit_empty|max_length[50]',
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

        $contestantModel = new ContestantModel();

        // Generate next contestant number for this event
        $maxNumberRow = $contestantModel
            ->selectMax('contestant_number')
            ->where('event_id', $eventId)
            ->first();

        $nextNumber = isset($maxNumberRow['contestant_number']) && $maxNumberRow['contestant_number'] !== null
            ? ((int) $maxNumberRow['contestant_number']) + 1
            : 1;

        $photoPath = null;
        $file      = $this->request->getFile('profile_photo');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            try {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/profiles', $newName);
                $photoPath = $newName;
            } catch (\Throwable $e) {
                log_message('error', 'Error uploading contestant photo: {error}', ['error' => $e->getMessage()]);
            }
        }

        $data = [
            'event_id'          => $eventId,
            'contestant_number' => $nextNumber,
            'first_name'        => $this->request->getPost('first_name'),
            'last_name'         => $this->request->getPost('last_name'),
            'email'             => $this->request->getPost('email'),
            'phone'             => $this->request->getPost('phone'),
            'age'               => $this->request->getPost('age') ?: null,
            'hometown'          => $this->request->getPost('hometown') ?: null,
            'status'            => 'active',
        ];

        if ($photoPath !== null) {
            $data['photo_path'] = $photoPath;
        }

        try {
            $contestantModel->insert($data);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Contestant added successfully.',
                ]);
            }

            return redirect()
                ->to("/events/{$eventId}/contestants")
                ->with('message', 'Contestant added successfully');
        } catch (\Throwable $e) {
            log_message('error', 'Error Creating Contestant: {error}', ['error' => $e->getMessage()]);

            if ($this->request->isAJAX()) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'An error occurred while adding the contestant. Please try again.',
                    ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while adding the contestant. Please try again.');
        }
    }
}
