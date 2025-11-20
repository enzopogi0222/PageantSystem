<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CriteriaModel;
use App\Models\RoundModel;
use App\Models\ScoreModel;

class Criteria extends BaseController
{
    private function getRoundOr404(int $roundId)
    {
        $roundModel = new RoundModel();
        $round = $roundModel->find($roundId);
        if (!$round) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Round not found']);
        }
        return $round;
    }

    private function guardEditable(array $round)
    {
        // Allow edits in 'upcoming' and 'active'; block when 'completed'
        if (($round['status'] ?? 'upcoming') === 'completed') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Cannot modify criteria for a completed round.']);
        }
        return null;
    }

    public function index($roundId)
    {
        $roundId = (int) $roundId;
        $round = $this->getRoundOr404($roundId);
        if ($round instanceof \CodeIgniter\HTTP\Response) {
            return $round; // early return with 404
        }

        $criteriaModel = new CriteriaModel();
        $items = $criteriaModel->where('round_id', $roundId)->orderBy('sort_order', 'ASC')->findAll();

        return $this->response->setJSON([
            'round' => $round,
            'criteria' => $items,
        ]);
    }

    public function store($roundId)
    {
        $roundId = (int) $roundId;
        $round = $this->getRoundOr404($roundId);
        if ($round instanceof \CodeIgniter\HTTP\Response) {
            return $round;
        }
        if ($resp = $this->guardEditable($round)) {
            return $resp;
        }

        $req = $this->request;
        $name = trim((string) $req->getPost('name'));
        $description = (string) $req->getPost('description');
        $weight = $req->getPost('weight');
        $maxScore = $req->getPost('max_score');
        $sortOrder = $req->getPost('sort_order');

        $errors = [];
        if ($name === '') {
            $errors[] = 'Name is required';
        }
        $weight = is_numeric($weight) ? (float) $weight : null;
        if ($weight === null || $weight < 0 || $weight > 100) {
            $errors[] = 'Weight must be between 0 and 100';
        }
        $maxScore = is_numeric($maxScore) ? (float) $maxScore : null;
        if ($maxScore === null || $maxScore <= 0) {
            $errors[] = 'Max score must be greater than 0';
        }
        $sortOrder = is_numeric($sortOrder) ? (int) $sortOrder : 0;

        if (!empty($errors)) {
            return $this->response->setStatusCode(422)->setJSON(['error' => implode('. ', $errors)]);
        }

        $criteriaModel = new CriteriaModel();
        try {
            $criteriaModel->insert([
                'round_id' => $roundId,
                'name' => $name,
                'description' => $description,
                'weight' => $weight,
                'max_score' => $maxScore,
                'sort_order' => $sortOrder,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to create criteria: ' . $e->getMessage()]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function update($id)
    {
        $id = (int) $id;
        $criteriaModel = new CriteriaModel();
        $existing = $criteriaModel->find($id);
        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Criteria not found']);
        }

        $round = $this->getRoundOr404((int) $existing['round_id']);
        if ($round instanceof \CodeIgniter\HTTP\Response) {
            return $round;
        }
        if ($resp = $this->guardEditable($round)) {
            return $resp;
        }

        $req = $this->request;
        $name = trim((string) $req->getPost('name', FILTER_UNSAFE_RAW) ?? $existing['name']);
        $description = $req->getPost('description', FILTER_UNSAFE_RAW) ?? $existing['description'];
        $weight = $req->getPost('weight');
        $maxScore = $req->getPost('max_score');
        $sortOrder = $req->getPost('sort_order');

        $data = [];
        if ($name !== '') { $data['name'] = $name; }
        if ($description !== null) { $data['description'] = (string) $description; }
        if ($weight !== null && $weight !== '') {
            if (!is_numeric($weight) || $weight < 0 || $weight > 100) {
                return $this->response->setStatusCode(422)->setJSON(['error' => 'Weight must be between 0 and 100']);
            }
            $data['weight'] = (float) $weight;
        }
        if ($maxScore !== null && $maxScore !== '') {
            if (!is_numeric($maxScore) || $maxScore <= 0) {
                return $this->response->setStatusCode(422)->setJSON(['error' => 'Max score must be greater than 0']);
            }
            $data['max_score'] = (float) $maxScore;
        }
        if ($sortOrder !== null && $sortOrder !== '') {
            if (!is_numeric($sortOrder)) {
                return $this->response->setStatusCode(422)->setJSON(['error' => 'Sort order must be numeric']);
            }
            $data['sort_order'] = (int) $sortOrder;
        }

        if (empty($data)) {
            return $this->response->setJSON(['success' => true]);
        }

        try {
            $criteriaModel->update($id, $data);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update criteria: ' . $e->getMessage()]);
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id)
    {
        $id = (int) $id;
        $criteriaModel = new CriteriaModel();
        $existing = $criteriaModel->find($id);
        if (!$existing) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Criteria not found']);
        }

        $round = $this->getRoundOr404((int) $existing['round_id']);
        if ($round instanceof \CodeIgniter\HTTP\Response) {
            return $round;
        }
        if (($round['status'] ?? 'upcoming') === 'completed') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Cannot delete criteria from a completed round.']);
        }

        // Guard: prevent delete if scores exist for this criteria
        $scoreModel = new ScoreModel();
        $hasScores = $scoreModel->where('criteria_id', $id)->first();
        if ($hasScores) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Cannot delete criteria that already has scores.']);
        }

        try {
            $criteriaModel->delete($id);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete criteria: ' . $e->getMessage()]);
        }

        return $this->response->setJSON(['success' => true]);
    }
}
