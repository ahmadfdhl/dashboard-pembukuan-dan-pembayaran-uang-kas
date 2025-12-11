<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        // Create test teacher
        $teacher = User::create([
            'name' => 'Guru Test',
            'email' => 'guru@test.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '081234567890',
            'is_active' => true,
        ]);
        
        // Create test class
        $class = Classes::create([
            'name' => 'A',
            'grade' => '12',
            'major' => 'IPA',
            'teacher_id' => $teacher->id,
        ]);
        
        // Create test students
        $students = [
            [
                'name' => 'Siswa 1',
                'email' => 'siswa1@test.com',
                'nisn' => '12345678901',
                'password' => Hash::make('password'),
                'role' => 'student',
                'class_id' => $class->id,
                'phone' => '081111111111',
                'is_active' => true,
            ],
            [
                'name' => 'Siswa 2',
                'email' => 'siswa2@test.com',
                'nisn' => '12345678902',
                'password' => Hash::make('password'),
                'role' => 'student',
                'class_id' => $class->id,
                'phone' => '081222222222',
                'is_active' => true,
            ],
            [
                'name' => 'Siswa Bendahara',
                'email' => 'bendahara@test.com',
                'nisn' => '12345678903',
                'password' => Hash::make('password'),
                'role' => 'treasurer',
                'class_id' => $class->id,
                'phone' => '081333333333',
                'is_active' => true,
            ],
        ];
        
        foreach ($students as $studentData) {
            User::create($studentData);
        }
        
        // Update class with treasurer (use withoutGlobalScopes to bypass soft delete check)
        $treasurer = User::withoutGlobalScopes()->where('email', 'bendahara@test.com')->first();
        $class->update(['treasurer_id' => $treasurer->id]);
        
        $this->command->info('Test data created successfully!');
        $this->command->info('Teacher: guru@test.com / password');
        $this->command->info('Student: siswa1@test.com / password');
        $this->command->info('Treasurer: bendahara@test.com / password');
    }
}