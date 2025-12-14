<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use Faker\Factory as Faker;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $studentNumber = 1;

        // Year 1 - 20 students (no specialization)
        for ($i = 1; $i <= 20; $i++) {
            Student::create([
                'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'year' => 1,
                'specialization' => null,
                'track' => null,
            ]);
        }

        // Year 2 - 20 students (no specialization)
        for ($i = 1; $i <= 20; $i++) {
            Student::create([
                'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'year' => 2,
                'specialization' => null,
                'track' => null,
            ]);
        }

        // Year 3 - 20 students per specialization
        $specializations = ['GI', 'GC', 'GE', 'GM'];
        foreach ($specializations as $spec) {
            for ($i = 1; $i <= 20; $i++) {
                Student::create([
                    'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'email' => $faker->unique()->safeEmail(),
                    'year' => 3,
                    'specialization' => $spec,
                    'track' => null,
                ]);
            }
        }

        // Year 4 - 20 students per specialization
        foreach ($specializations as $spec) {
            // For GI: 20 per track (DEV and AI)
            if ($spec === 'GI') {
                foreach (['DEV', 'AI'] as $track) {
                    for ($i = 1; $i <= 20; $i++) {
                        Student::create([
                            'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                            'first_name' => $faker->firstName(),
                            'last_name' => $faker->lastName(),
                            'email' => $faker->unique()->safeEmail(),
                            'year' => 4,
                            'specialization' => $spec,
                            'track' => $track,
                        ]);
                    }
                }
            } else {
                // Other specializations: 20 students without track
                for ($i = 1; $i <= 20; $i++) {
                    Student::create([
                        'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                        'first_name' => $faker->firstName(),
                        'last_name' => $faker->lastName(),
                        'email' => $faker->unique()->safeEmail(),
                        'year' => 4,
                        'specialization' => $spec,
                        'track' => null,
                    ]);
                }
            }
        }

        // Year 5 - 20 students per specialization
        foreach ($specializations as $spec) {
            // For GI: 20 per track (DEV and AI)
            if ($spec === 'GI') {
                foreach (['DEV', 'AI'] as $track) {
                    for ($i = 1; $i <= 20; $i++) {
                        Student::create([
                            'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                            'first_name' => $faker->firstName(),
                            'last_name' => $faker->lastName(),
                            'email' => $faker->unique()->safeEmail(),
                            'year' => 5,
                            'specialization' => $spec,
                            'track' => $track,
                        ]);
                    }
                }
            } else {
                // Other specializations: 20 students without track
                for ($i = 1; $i <= 20; $i++) {
                    Student::create([
                        'code' => 'STU' . str_pad($studentNumber++, 6, '0', STR_PAD_LEFT),
                        'first_name' => $faker->firstName(),
                        'last_name' => $faker->lastName(),
                        'email' => $faker->unique()->safeEmail(),
                        'year' => 5,
                        'specialization' => $spec,
                        'track' => null,
                    ]);
                }
            }
        }

        $this->command->info('Students seeded successfully!');
        $this->command->info('Total students created: ' . ($studentNumber - 1));
        $this->command->info('Year 1: 20 students');
        $this->command->info('Year 2: 20 students');
        $this->command->info('Year 3: 80 students (20 per specialization)');
        $this->command->info('Year 4: 140 students (40 for GI [20 DEV + 20 AI], 20 for each other spec)');
        $this->command->info('Year 5: 140 students (40 for GI [20 DEV + 20 AI], 20 for each other spec)');
    }
}

