<?php

namespace App\Controllers;

use CodeIgniter\Controller;

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
