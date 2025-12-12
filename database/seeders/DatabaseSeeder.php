<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
            'phone' => '08213142142',
            'is_active' => true,
        ]);

        $this->call([
            DummyOrderSeeder::class,
            TeacherSeeder::class,
        ]);
    }
}
