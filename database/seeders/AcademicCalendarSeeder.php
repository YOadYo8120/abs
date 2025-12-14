<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('academic_calendar')->truncate();

        // Semester 1 (2025-2026) - 13 weeks
        $semester1 = [
            ['year' => 2025, 'semester' => 1, 'week_number' => 1, 'start_date' => '2025-09-22', 'end_date' => '2025-09-28', 'description' => 'Démarrage des cours du Cycle ingénieur'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 2, 'start_date' => '2025-09-29', 'end_date' => '2025-10-05', 'description' => 'Démarage des cours des Classes Préparatoires'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 3, 'start_date' => '2025-10-06', 'end_date' => '2025-10-12', 'description' => 'Répartition et Affichage des Mini-projets'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 4, 'start_date' => '2025-10-13', 'end_date' => '2025-10-19', 'description' => 'Evaluation des stages 3A et 4A'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 5, 'start_date' => '2025-10-20', 'end_date' => '2025-10-26', 'description' => null],
            ['year' => 2025, 'semester' => 1, 'week_number' => 6, 'start_date' => '2025-10-27', 'end_date' => '2025-11-02', 'description' => 'Affichages des Plannings DS1'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 7, 'start_date' => '2025-11-03', 'end_date' => '2025-11-09', 'description' => 'Marche Verte'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 8, 'start_date' => '2025-11-10', 'end_date' => '2025-11-16', 'description' => null],
            ['year' => 2025, 'semester' => 1, 'week_number' => 9, 'start_date' => '2025-11-17', 'end_date' => '2025-11-23', 'description' => 'Fête de l\'indépendance'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 10, 'start_date' => '2025-12-01', 'end_date' => '2025-12-07', 'description' => null],
            ['year' => 2025, 'semester' => 1, 'week_number' => 11, 'start_date' => '2025-12-08', 'end_date' => '2025-12-14', 'description' => 'FORUM'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 12, 'start_date' => '2025-12-15', 'end_date' => '2025-12-21', 'description' => 'Affichages des Plannings DS2 et du Rattrapage'],
            ['year' => 2025, 'semester' => 1, 'week_number' => 13, 'start_date' => '2025-12-22', 'end_date' => '2025-12-28', 'description' => 'Arrêt Cours'],
        ];

        // Semester 2 (2025-2026) - 14 weeks
        $semester2 = [
            ['year' => 2025, 'semester' => 2, 'week_number' => 1, 'start_date' => '2026-02-02', 'end_date' => '2026-02-08', 'description' => 'Démarrage des cours'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 2, 'start_date' => '2026-02-09', 'end_date' => '2026-02-15', 'description' => 'Affichage des résultats après rattrapage'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 3, 'start_date' => '2026-02-16', 'end_date' => '2026-02-22', 'description' => null],
            ['year' => 2025, 'semester' => 2, 'week_number' => 4, 'start_date' => '2026-02-23', 'end_date' => '2026-03-01', 'description' => null],
            ['year' => 2025, 'semester' => 2, 'week_number' => 5, 'start_date' => '2026-03-02', 'end_date' => '2026-03-08', 'description' => 'Affichages des Plannings DS1'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 6, 'start_date' => '2026-03-09', 'end_date' => '2026-03-15', 'description' => null],
            ['year' => 2025, 'semester' => 2, 'week_number' => 7, 'start_date' => '2026-03-16', 'end_date' => '2026-03-22', 'description' => null],
            ['year' => 2025, 'semester' => 2, 'week_number' => 8, 'start_date' => '2026-03-30', 'end_date' => '2026-04-05', 'description' => 'Démarrage des soutenence des mini-projets'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 9, 'start_date' => '2026-04-06', 'end_date' => '2026-04-12', 'description' => 'Affichages des Plannings DS2 et du Rattrapage'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 10, 'start_date' => '2026-04-13', 'end_date' => '2026-04-19', 'description' => null],
            ['year' => 2025, 'semester' => 2, 'week_number' => 11, 'start_date' => '2026-04-20', 'end_date' => '2026-04-26', 'description' => null],
            ['year' => 2025, 'semester' => 2, 'week_number' => 12, 'start_date' => '2026-04-27', 'end_date' => '2026-05-03', 'description' => 'Fête de travail'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 13, 'start_date' => '2026-05-04', 'end_date' => '2026-05-10', 'description' => 'Vacances de Printemps'],
            ['year' => 2025, 'semester' => 2, 'week_number' => 14, 'start_date' => '2026-05-11', 'end_date' => '2026-05-17', 'description' => 'Arrêt Cours'],
        ];

        // Insert data
        foreach (array_merge($semester1, $semester2) as $week) {
            DB::table('academic_calendar')->insert(array_merge($week, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Academic calendar seeded successfully!');
        $this->command->info('Semester 1: 13 weeks (22/09/2025 - 28/12/2025)');
        $this->command->info('Semester 2: 14 weeks (02/02/2026 - 17/05/2026)');
    }
}
