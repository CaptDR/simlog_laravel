<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoUsers = [
            [
                'username'  => 'superadmin',
                'name'      => 'Admin Sistem BPBD',
                'role'      => 'superadmin',
                'nip'       => '19880101 201503 1 001',
                'is_active' => true,
            ],
            [
                'username'  => 'kasie',
                'name'      => 'Kepala Seksi Darlog',
                'role'      => 'kasie',
                'nip'       => '19800514 201001 1 002',
                'is_active' => true,
            ],
            [
                'username'  => 'logistik',
                'name'      => 'Petugas Gudang Logistik',
                'role'      => 'logistik',
                'nip'       => '19940821 202012 1 003',
                'is_active' => true,
            ],
            [
                'username'  => 'trc',
                'name'      => 'Anggota Tim TRC',
                'role'      => 'trc',
                'nip'       => '19971105 202203 1 004',
                'is_active' => true,
            ],
        ];

        foreach ($demoUsers as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                [
                    'name'      => $userData['name'],
                    'role'      => $userData['role'],
                    'nip'       => $userData['nip'],
                    'is_active' => $userData['is_active'],
                    'password'  => Hash::make('password123'),
                ]
            );
        }
    }
}
