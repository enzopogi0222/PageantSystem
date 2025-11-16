<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventModel;

class Events extends BaseController
{
    public function create()
    {
        return view('events/create');
    }

    public function store()
    {
        $request = service('request');
        $session = session();

        $data = [
            'name'        => trim((string) $request->getPost('name')),
            'description' => (string) $request->getPost('description'),
            'event_date'  => $request->getPost('event_date') ?: null,
            'location'    => trim((string) $request->getPost('location')) ?: null,
        ];

        if ($data['name'] === '') {
            $session->setFlashdata('error', 'Event name is required.');
            return redirect()->back()->withInput();
        }

        $model = new EventModel();
        $model->insert($data);

        $session->setFlashdata('success', 'Event "' . $data['name'] . '" created.');
        return redirect()->to('/dashboard');
    }

    public function activate($id)
    {
        $session = session();
        $model = new EventModel();
        // Clear previous active
        $model->where('is_active', 1)->set(['is_active' => 0])->update();
        // Set selected active
        $updated = $model->update((int) $id, ['is_active' => 1]);
        if ($updated === false) {
            $session->setFlashdata('error', 'Failed to activate event.');
        } else {
            $event = $model->find((int) $id);
            $session->setFlashdata('success', 'Activated event: ' . ($event['name'] ?? 'ID ' . $id));
        }
        return redirect()->to('/admin');
    }
}
