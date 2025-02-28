<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'email' => 'superadmin@bomba.gov.my',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'negeri' => 'IBU PEJABAT',
            'verified' => true,
        ]);
    }
}

