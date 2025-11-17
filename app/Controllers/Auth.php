<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\JudgeModel;
use App\Models\EventModel;

class Auth extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $request = service('request');
        $session = session();

        $identifier = trim((string) $request->getPost('identifier'));
        $password   = (string) $request->getPost('password');

        if ($identifier === '' || $password === '') {
            $session->setFlashdata('error', 'Please enter your username/email and password.');
            return redirect()->back()->withInput();
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $user = $builder
            ->groupStart()
                ->where('email', $identifier)
                ->orWhere('username', $identifier)
            ->groupEnd()
            ->get(1)
            ->getRow();

        if (!$user || !password_verify($password, $user->password_hash)) {
            $session->setFlashdata('error', 'Invalid credentials.');
            return redirect()->back()->withInput();
        }

        if (($user->role ?? null) === 'judge') {
            $judgeModel = new JudgeModel();
            $eventModel = new EventModel();

            $judge = $judgeModel
                ->where('user_id', $user->id)
                ->get(1)
                ->getRowArray();

            if (!$judge || !isset($judge['event_id'])) {
                $session->setFlashdata('error', 'Your judge account is not linked to an event. Please contact the administrator.');
                return redirect()->back()->withInput();
            }

            $event = $eventModel->find($judge['event_id']);

            if (!$event || empty($event['is_active'])) {
                $session->setFlashdata('error', 'The event assigned to your judge account is not active. You cannot log in at this time.');
                return redirect()->back()->withInput();
            }
        }

        $session->set([
            'user_id'  => $user->id,
            'username' => $user->username,
            'role'     => $user->role ?? null,
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/admin');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
