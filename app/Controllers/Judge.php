<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JudgeModel;
use App\Models\EventModel;
use App\Models\ContestantModel;
use App\Models\RoundModel;
use App\Models\CriteriaModel;

class Judge extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $userId = (int) ($session->get('user_id') ?? 0);
        if (!$userId) {
            return redirect()->to('/login');
        }

        $judgeModel = new JudgeModel();
        $eventModel = new EventModel();
        $contestantModel = new ContestantModel();
        $roundModel = new RoundModel();
        $criteriaModel = new CriteriaModel();

        // Find judge and assigned event
        $judge = $judgeModel->where('user_id', $userId)->get(1)->getRowArray();
        if (!$judge) {
            return redirect()->to('/logout');
        }

        // Keep active event in session for header links/context
        $session->set('active_event_id', (int)$judge['event_id']);

        $event = $eventModel->find($judge['event_id']);
        if (!$event || empty($event['is_active'])) {
            $session->setFlashdata('error', 'Your assigned event is not active.');
            return redirect()->to('/logout');
        }

        // Fetch view-only data for the event
        $contestants = $contestantModel
            ->where('event_id', $judge['event_id'])
            ->orderBy('contestant_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll(8); // preview up to 8

        // Normalize photo URLs for contestants
        foreach ($contestants as &$c) {
            $raw = $c['profile_photo'] ?? ($c['photo_path'] ?? '');
            $c['photo_url'] = $this->resolvePhotoUrl($raw);
        }

        $rounds = $roundModel
            ->where('event_id', $judge['event_id'])
            ->orderBy('round_order', 'ASC')
            ->findAll();

        // Map criteria per round
        $criteriaByRound = [];
        if (!empty($rounds)) {
            $roundIds = array_map(fn($r) => (int)$r['id'], $rounds);
            if (!empty($roundIds)) {
                $allCriteria = $criteriaModel
                    ->whereIn('round_id', $roundIds)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
                foreach ($allCriteria as $c) {
                    $criteriaByRound[$c['round_id']][] = $c;
                }
            }
        }

        $data = [
            'title' => 'Judge Dashboard',
            'system_name' => $event['name'] ?? 'Event',
            'page_title' => 'Judge',
            'logout_url' => base_url('logout'),
            'hide_center_nav' => true,
            'user_greeting' => 'Judge Dashboard',
            'event' => $event,
            'contestants' => $contestants,
            'rounds' => $rounds,
            'criteriaByRound' => $criteriaByRound,
            'message' => $session->getFlashdata('message') ?? '',
            'error' => $session->getFlashdata('error') ?? '',
        ];

        return view('Judge/dashboard', $data);
    }

    public function contestant($id)
    {
        $session = session();
        $userId = (int) ($session->get('user_id') ?? 0);
        if (!$userId) {
            return redirect()->to('/login');
        }

        $contestantId = (int) $id;
        $judgeModel = new JudgeModel();
        $eventModel = new EventModel();
        $contestantModel = new ContestantModel();

        $judge = $judgeModel->where('user_id', $userId)->get(1)->getRowArray();
        if (!$judge) {
            return redirect()->to('/logout');
        }

        // Keep active event in session for header links/context
        $session->set('active_event_id', (int)$judge['event_id']);

        $contestant = $contestantModel->find($contestantId);
        if (!$contestant || (int)($contestant['event_id'] ?? 0) !== (int)$judge['event_id']) {
            $session->setFlashdata('error', 'Contestant not found for your event.');
            return redirect()->to('/judge');
        }

        $event = $eventModel->find($judge['event_id']);

        $data = [
            'title' => 'Contestant Info',
            'system_name' => $event['name'] ?? 'Event',
            'page_title' => 'Judge',
            'logout_url' => base_url('logout'),
            'hide_center_nav' => true,
            'user_greeting' => 'Contestant Info',
            'event' => $event,
            'contestant' => array_merge($contestant, [
                'photo_url' => $this->resolvePhotoUrl(($contestant['profile_photo'] ?? ($contestant['photo_path'] ?? ''))),
            ]),
            'message' => $session->getFlashdata('message') ?? '',
            'error' => $session->getFlashdata('error') ?? '',
        ];

        return view('Judge/contestant_view', $data);
    }

    public function contestants()
    {
        $session = session();
        $userId = (int) ($session->get('user_id') ?? 0);
        if (!$userId) {
            return redirect()->to('/login');
        }

        $judgeModel = new JudgeModel();
        $eventModel = new EventModel();
        $contestantModel = new ContestantModel();

        $judge = $judgeModel->where('user_id', $userId)->get(1)->getRowArray();
        if (!$judge) {
            return redirect()->to('/logout');
        }

        $event = $eventModel->find($judge['event_id']);

        $contestants = $contestantModel
            ->where('event_id', $judge['event_id'])
            ->orderBy('contestant_number', 'ASC')
            ->findAll();

        foreach ($contestants as &$c) {
            $raw = $c['profile_photo'] ?? ($c['photo_path'] ?? '');
            $c['photo_url'] = $this->resolvePhotoUrl($raw);
        }

        $data = [
            'title' => 'Contestants',
            'system_name' => $event['name'] ?? 'Event',
            'page_title' => 'Judge',
            'logout_url' => base_url('logout'),
            'hide_center_nav' => true,
            'user_greeting' => 'Contestants',
            'event' => $event,
            'contestants' => $contestants,
        ];

        return view('Judge/contestants', $data);
    }

    private function resolvePhotoUrl(?string $path): ?string
    {
        $path = trim((string)$path);
        if ($path === '') {
            return null;
        }
        // If already absolute URL
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        // If value contains a slash, assume it's a relative path from public and encode segments
        if (strpos($path, '/') !== false || strpos($path, '\\') !== false) {
            $norm = ltrim(str_replace('\\', '/', $path), '/');
            $segments = array_map(static function ($seg) {
                return rawurlencode($seg);
            }, explode('/', $norm));
            return base_url(implode('/', $segments));
        }
        // Otherwise treat as filename as Admin does: uploads/profiles/<filename>
        return base_url('uploads/profiles/' . rawurlencode($path));
    }
}
