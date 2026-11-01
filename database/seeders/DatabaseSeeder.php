<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DataPakarSeeder::class,
            DataPakarTambahanSeeder::class,
        ]);

        $admin = User::firstOrNew(['email' => 'admin@example.com']);
        $admin->forceFill([
            'name'     => 'Admin',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ])->save();
    }
}