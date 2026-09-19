<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tuhomestay.com'],
            [
                'nama_lengkap' => 'Admin TuhomesTay',
                'password'     => Hash::make('admin123'),
                'no_telp'      => '0811111111',
                'role'         => 'admin',
                'saldo_koin'   => 0,
            ]
        );

        $this->command->info('✅ Admin dibuat: admin@tuhomestay.com / admin123');
    }
}