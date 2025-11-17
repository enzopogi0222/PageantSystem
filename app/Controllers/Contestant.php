<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ContestantModel;

class Contestant extends BaseController
{
    public function index($eventId = null)
    {
        $model = new ContestantModel();
        // Determine event: use route param if provided, otherwise active event
        if ($eventId === null) {
            try {
                $em = new \App\Models\EventModel();
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

        $contestants = $eventId ? $model->where('event_id', $eventId)->findAll() : [];
        $data = [
            'primary_color' => '#6f42c1',
            'secondary_color' => '#495057',
            'accent_color' => '#28a745',
            'contestants' => $contestants,
            'success_message' => session()->getFlashdata('success') ?? '',
            'error_message' => session()->getFlashdata('error') ?? '',
            'csrf_token' => csrf_hash()
        ];
        
        return view('Admin/contestants', $data);
    }

    public function store()
    {
        $model = new ContestantModel();
        // Resolve active event id
        $eventId = null;
        try {
            $em = new \App\Models\EventModel();
            $active = $em->where('is_active', 1)->orderBy('id', 'DESC')->first();
            if ($active) {
                $eventId = (int) $active['id'];
            }
        } catch (\Throwable $e) {}

        if (!$eventId) {
            return redirect()->to('contestant')->with('error', 'No active event. Please activate an event first.');
        }

        $data = [
            'event_id' => $eventId,
            'contestant_number' => $this->request->getPost('contestant_number'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'age' => $this->request->getPost('age'),
            'hometown' => $this->request->getPost('hometown'),
            'height' => $this->request->getPost('height'),
            'weight' => $this->request->getPost('weight'),
            'education' => $this->request->getPost('education'),
            'occupation' => $this->request->getPost('occupation'),
            'hobbies' => $this->request->getPost('hobbies'),
            'bio' => $this->request->getPost('bio'),
            'gender' => $this->request->getPost('gender'),
            'status' => 'active',
        ];

        // Handle optional profile photo upload
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'profiles';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $newName = $file->getRandomName();
            if ($file->move($uploadDir, $newName)) {
                $data['photo_path'] = $newName;
            }
        }

        try {
            $result = $model->insert($data);
            if ($result === false) {
                $errors = $model->errors();
                $msg = 'Failed to add contestant.';
                if (!empty($errors)) {
                    $msg .= ' ' . implode(' ', array_values($errors));
                }
                return redirect()->to('contestant')->with('error', $msg);
            }
            return redirect()->to('contestant')->with('success', 'Contestant added successfully.');
        } catch (\Throwable $e) {
            return redirect()->to('contestant')->with('error', 'Failed to add contestant. ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        $model = new ContestantModel();
        // Resolve active event id to constrain updates
        $eventId = null;
        try {
            $em = new \App\Models\EventModel();
            $active = $em->where('is_active', 1)->orderBy('id', 'DESC')->first();
            if ($active) {
                $eventId = (int) $active['id'];
            }
        } catch (\Throwable $e) {}

        if (!$eventId) {
            return redirect()->to('contestant')->with('error', 'No active event.');
        }

        $id = (int) $id;
        $existing = $model->where('id', $id)->where('event_id', $eventId)->first();
        if (!$existing) {
            return redirect()->to('contestant')->with('error', 'Contestant not found for current event.');
        }

        $data = [
            'contestant_number' => $this->request->getPost('contestant_number'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'age' => $this->request->getPost('age'),
            'hometown' => $this->request->getPost('hometown'),
            'height' => $this->request->getPost('height'),
            'weight' => $this->request->getPost('weight'),
            'education' => $this->request->getPost('education'),
            'occupation' => $this->request->getPost('occupation'),
            'hobbies' => $this->request->getPost('hobbies'),
            'bio' => $this->request->getPost('bio'),
            'gender' => $this->request->getPost('gender'),
            'status' => $this->request->getPost('status') ?: $existing['status'],
        ];

        // Optional new photo
        $file = $this->request->getFile('profile_photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'profiles';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $newName = $file->getRandomName();
            if ($file->move($uploadDir, $newName)) {
                $data['photo_path'] = $newName;
            }
        }

        try {
            $result = $model->update($id, $data);
            if ($result === false) {
                $errors = $model->errors();
                $msg = 'Failed to update contestant.';
                if (!empty($errors)) {
                    $msg .= ' ' . implode(' ', array_values($errors));
                }
                return redirect()->to('contestant')->with('error', $msg);
            }
            return redirect()->to('contestant')->with('success', 'Contestant updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->to('contestant')->with('error', 'Failed to update contestant. ' . $e->getMessage());
        }
    }
}
