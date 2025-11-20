<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\RoundModel;
use App\Models\CriteriaModel;

class Rounds extends BaseController
{
    public function index($eventId = null)
    {
        $eventModel = new EventModel();
        $roundModel = new RoundModel();

        if ($eventId === null) {
            $active = $eventModel->where('is_active', 1)->orderBy('id', 'DESC')->first();
            $eventId = $active['id'] ?? null;
        } else {
            $eventId = (int) $eventId;
        }

        $debugJson = (bool) $this->request->getGet('__json');
        $filterEvent = $this->request->getGet('__event');
        if ($filterEvent !== null && $filterEvent !== '') {
            $eventId = (int) $filterEvent;
        }

        if ($this->request->isAJAX() || str_contains((string) $this->request->getHeaderLine('Accept'), 'application/json') || $debugJson) {
            if (!$eventId) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'No active event found.']);
            }
            $rounds = $roundModel->where('event_id', $eventId)->orderBy('round_order', 'ASC')->findAll();
            return $this->response->setJSON(['event_id' => $eventId, 'rounds' => $rounds]);
        }

        $latestEventName = null;
        $activeEventId   = $eventId;
        try {
            if ($eventId) {
                $ev = $eventModel->find($eventId);
                $latestEventName = $ev['name'] ?? null;
            }
        } catch (\Throwable $e) {}

        if (!$activeEventId) {
            $data = [
                'system_name'           => 'Pageant Management System',
                'active_event_id'       => null,
                'primary_color'         => '#6f42c1',
                'secondary_color'       => '#495057',
                'accent_color'          => '#28a745',
                'rounds_with_criteria'  => [],
                'message'               => session()->getFlashdata('message') ?? '',
                'error'                 => 'No active event. Please activate an event first.',
                'current_page'          => 'rounds',
            ];
            return view('Admin/rounds', $data);
        }

        $roundsWithCriteria = [];
        if ($activeEventId) {
            try {
                $criteriaModel = new CriteriaModel();
                $rounds = $roundModel->where('event_id', $activeEventId)->orderBy('round_order', 'ASC')->findAll();
                foreach ($rounds as $r) {
                    $crit = $criteriaModel->where('round_id', (int)$r['id'])->orderBy('sort_order','ASC')->findAll();
                    $crit = array_map(function($c){
                        return [
                            'id' => $c['id'],
                            'name' => $c['name'],
                            'description' => $c['description'],
                            'weight_percentage' => isset($c['weight']) ? (float)$c['weight'] : 0,
                            'max_score' => $c['max_score'],
                            'criteria_order' => $c['sort_order'] ?? 0,
                        ];
                    }, $crit);
                    $r['criteria'] = $crit;
                    $roundsWithCriteria[] = $r;
                }
            } catch (\Throwable $e) {}
        }

        $data = [
            'system_name'           => $latestEventName ?: 'Pageant Management System',
            'active_event_id'       => $activeEventId,
            'primary_color'         => '#6f42c1',
            'secondary_color'       => '#495057',
            'accent_color'          => '#28a745',
            'rounds_with_criteria'  => $roundsWithCriteria,
            'message'               => session()->getFlashdata('message') ?? '',
            'error'                 => session()->getFlashdata('error') ?? '',
            'current_page'          => 'rounds',
        ];
        return view('Admin/rounds', $data);
    }

    public function store($eventId)
    {
        $eventId = (int) $eventId;
        if ($eventId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid event id']);
        }
        $name = trim((string) $this->request->getPost('name'));
        $description = (string) $this->request->getPost('description');
        $roundOrder = $this->request->getPost('round_order');
        $status = $this->request->getPost('status') ?: 'upcoming';
        $maxScore = $this->request->getPost('max_score');
        $startDate = $this->request->getPost('start_date') ?: null;
        $endDate = $this->request->getPost('end_date') ?: null;

        $errors = [];
        if ($name === '') $errors[] = 'Name is required';
        $roundOrder = is_numeric($roundOrder) ? (int) $roundOrder : 0;
        $maxScore = is_numeric($maxScore) ? (float) $maxScore : 100.0;
        if (!in_array($status, ['upcoming', 'active', 'completed'], true)) {
            $errors[] = 'Invalid status';
        }
        if (!empty($errors)) {
            return $this->response->setStatusCode(422)->setJSON(['error' => implode('. ', $errors)]);
        }

        $roundModel = new RoundModel();
        try {
            $roundModel->insert([
                'event_id' => $eventId,
                'name' => $name,
                'description' => $description,
                'round_order' => $roundOrder,
                'status' => $status,
                'max_score' => $maxScore,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create round: ' . $e->getMessage()]);
        }
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true]);
        }
        session()->setFlashdata('message', 'Round created successfully.');
        return redirect()->to('/rounds');
    }

    public function storeActive()
    {
        $eventModel = new EventModel();
        $active = $eventModel->where('is_active', 1)->orderBy('id', 'DESC')->first();
        $eventId = (int) ($active['id'] ?? 0);
        if ($eventId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No active event found. Activate an event first.']);
        }
        return $this->store($eventId);
    }

    public function update($id)
    {
        $id = (int) $id;
        $roundModel = new RoundModel();
        $existing = $roundModel->find($id);
        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Round not found']);
        }

        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $roundOrder = $this->request->getPost('round_order');
        $status = $this->request->getPost('status');
        $maxScore = $this->request->getPost('max_score');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');

        $data = [];
        if ($name !== null) { $name = trim((string)$name); if ($name==='') return $this->response->setStatusCode(422)->setJSON(['error'=>'Name cannot be empty']); $data['name']=$name; }
        if ($description !== null) { $data['description'] = (string) $description; }
        if ($roundOrder !== null) { if (!is_numeric($roundOrder)) return $this->response->setStatusCode(422)->setJSON(['error'=>'round_order must be numeric']); $data['round_order']=(int)$roundOrder; }
        if ($status !== null) { if (!in_array($status, ['upcoming','active','completed'], true)) return $this->response->setStatusCode(422)->setJSON(['error'=>'Invalid status']); $data['status']=$status; }
        if ($maxScore !== null) { if (!is_numeric($maxScore) || $maxScore<=0) return $this->response->setStatusCode(422)->setJSON(['error'=>'max_score must be > 0']); $data['max_score']=(float)$maxScore; }
        if ($startDate !== null) { $data['start_date'] = $startDate ?: null; }
        if ($endDate !== null) { $data['end_date'] = $endDate ?: null; }

        if (empty($data)) {
            if ($this->request->isAJAX()) { return $this->response->setJSON(['success'=>true]); }
            session()->setFlashdata('message', 'No changes to update.');
            return redirect()->to('/rounds');
        }

        try {
            $roundModel->update($id, $data);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update round: ' . $e->getMessage()]);
        }
        if ($this->request->isAJAX()) { return $this->response->setJSON(['success' => true]); }
        session()->setFlashdata('message', 'Round updated successfully.');
        return redirect()->to('/rounds');
    }

    public function delete($id)
    {
        $id = (int) $id;
        $roundModel = new RoundModel();
        $criteriaModel = new CriteriaModel();
        $existing = $roundModel->find($id);
        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Round not found']);
        }
        $hasCriteria = $criteriaModel->where('round_id', $id)->first();
        if ($hasCriteria) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Cannot delete round that has criteria. Delete criteria first.']);
        }
        try {
            $roundModel->delete($id);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete round: ' . $e->getMessage()]);
        }
        if ($this->request->isAJAX()) { return $this->response->setJSON(['success' => true]); }
        session()->setFlashdata('message', 'Round deleted successfully.');
        return redirect()->to('/rounds');
    }

    public function toggleStatus($id)
    {
        $id = (int) $id;
        $roundModel = new RoundModel();
        $existing = $roundModel->find($id);
        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Round not found']);
        }
        $status = $this->request->getPost('status');
        if (!in_array($status, ['upcoming','active','completed'], true)) {
            return $this->response->setStatusCode(422)->setJSON(['error' => 'Invalid status']);
        }
        try {
            $roundModel->update($id, ['status' => $status]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update status: ' . $e->getMessage()]);
        }
        if ($this->request->isAJAX()) { return $this->response->setJSON(['success' => true]); }
        session()->setFlashdata('message', 'Round status updated.');
        return redirect()->to('/rounds');
    }
}
