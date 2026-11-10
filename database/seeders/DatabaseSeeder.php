<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\OfficeLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        OfficeLocation::updateOrCreate(
            ['name' => 'Kantor Klik Media Nusantara'],
            [
                'latitude' => -6.33073153,
                'longitude' => 106.71568283,
                'radius_meters' => 100,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@klikmedia.test'],
            [
                'name' => 'Admin Klik Media',
                'password' => Hash::make('password123'),
                'plain_password' => 'password123',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswa@klikmedia.test'],
            [
                'name' => 'Siswa Magang',
                'password' => Hash::make('password123'),
                'plain_password' => 'password123',
                'role' => 'siswa',
            ]
        );
    }
}
