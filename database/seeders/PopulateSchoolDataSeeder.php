<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PopulateSchoolDataSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = [];
        $password = Hash::make('Password@2025');

        // Create 6 admin accounts
        $adminUsers = [];
        for ($i = 1; $i <= 6; $i++) {
            $email = "admin{$i}@abs.edu";
            $adminPassword = "Admin" . str_pad($i, 2, '0', STR_PAD_LEFT) . "@2025";

            $adminUsers[] = [
                'first_name' => "Admin",
                'last_name' => "User {$i}",
                'name' => "Admin User {$i}",
                'email' => $email,
                'password' => $password,
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $credentials[] = ['type' => 'Admin', 'email' => $email, 'password' => $adminPassword];
        }
        User::insert($adminUsers);
        $this->command->info('✓ Created 6 admin accounts');

        // Year 1
        $this->createStudents(1, 20, null, null, 'CP1-', $password, $credentials);
        $this->command->info('✓ Created 20 students for Year 1 (CP1)');

        // Year 2
        $this->createStudents(2, 20, null, null, 'CP2-', $password, $credentials);
        $this->command->info('✓ Created 20 students for Year 2 (CP2)');

        // Years 3-5
        $specializations = ['GI', 'GRT', 'GInd', 'GM', 'GA', 'GPM'];

        foreach ($specializations as $spec) {
            $this->createStudents(3, 20, $spec, null, "Y3-{$spec}-", $password, $credentials);
        }
        $this->command->info('✓ Created 120 students for Year 3');

        foreach ($specializations as $spec) {
            if ($spec === 'GI') {
                $this->createStudents(4, 10, $spec, 'AI', "Y4-{$spec}-AI-", $password, $credentials);
                $this->createStudents(4, 10, $spec, 'DEV', "Y4-{$spec}-DEV-", $password, $credentials);
            } else {
                $this->createStudents(4, 20, $spec, null, "Y4-{$spec}-", $password, $credentials);
            }
        }
        $this->command->info('✓ Created 120 students for Year 4');

        foreach ($specializations as $spec) {
            if ($spec === 'GI') {
                $this->createStudents(5, 10, $spec, 'AI', "Y5-{$spec}-AI-", $password, $credentials);
                $this->createStudents(5, 10, $spec, 'DEV', "Y5-{$spec}-DEV-", $password, $credentials);
            } else {
                $this->createStudents(5, 20, $spec, null, "Y5-{$spec}-", $password, $credentials);
            }
        }
        $this->command->info('✓ Created 120 students for Year 5');

        // Teachers
        $teacherUsers = [];
        for ($year = 1; $year <= 5; $year++) {
            for ($i = 1; $i <= 7; $i++) {
                $email = "teacher.y{$year}.{$i}@abs.edu";
                $teacherPassword = 'Teacher' . str_pad((($year-1) * 7 + $i), 2, '0', STR_PAD_LEFT) . '@2025';
                $teacherUsers[] = [
                    'first_name' => "Teacher",
                    'last_name' => "Year{$year}-{$i}",
                    'name' => "Teacher Year{$year}-{$i}",
                    'email' => $email,
                    'password' => $password,
                    'role' => 'teacher',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $credentials[] = ['type' => "Teacher Year {$year}", 'email' => $email, 'password' => $teacherPassword];
            }
        }
        User::insert($teacherUsers);
        $this->command->info('✓ Created 35 teachers');

        file_put_contents(storage_path('app/credentials.json'), json_encode($credentials, JSON_PRETTY_PRINT));
        $this->command->info('✓ Total: 6 admins, 400 students, 35 teachers = 441 users');
    }

    private function createStudents($year, $count, $spec, $track, $prefix, $password, &$credentials)
    {
        $users = [];
        for ($i = 1; $i <= $count; $i++) {
            $code = $prefix . str_pad($i, 3, '0', STR_PAD_LEFT);
            $email = strtolower($code) . '@student.abs.edu';
            $users[] = [
                'first_name' => "Student",
                'last_name' => $code,
                'name' => "Student {$code}",
                'email' => $email,
                'password' => $password,
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $credentials[] = [
                'type' => "Student Year {$year}" . ($spec ? " - {$spec}" : '') . ($track ? " ({$track})" : ''),
                'code' => $code,
                'email' => $email,
                'password' => 'Student' . str_pad($i, 2, '0', STR_PAD_LEFT) . '@2025',
            ];
        }

        User::insert($users);
        $userIds = DB::table('users')->whereIn('email', array_column($users, 'email'))->pluck('id', 'email');

        $students = [];
        foreach ($users as $user) {
            $students[] = [
                'user_id' => $userIds[$user['email']],
                'year' => $year,
                'specialization' => $spec,
                'track' => $track,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Student::insert($students);
    }
}
