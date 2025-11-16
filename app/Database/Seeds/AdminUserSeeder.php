<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $users = $this->db->table('users');

        // Configure your desired admin credentials here
        $username = 'admin';
        $email = 'admin@example.com';
        $plainPassword = 'Admin@12345';

        // Avoid duplicate seeding by checking email or username
        $exists = $users
            ->groupStart()
                ->where('email', $email)
                ->orWhere('username', $username)
            ->groupEnd()
            ->get(1)
            ->getRow();

        if ($exists) {
            // Already seeded or a user exists with same credentials
            return;
        }

        $now = date('Y-m-d H:i:s');

        $data = [
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($plainPassword, PASSWORD_DEFAULT),
            'first_name'    => 'Admin',
            'last_name'     => 'User',
            'role'          => 'admin',
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        $users->insert($data);
    }
}
