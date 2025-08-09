<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (admin) to assign activities to
        $user = \App\Models\User::first();

        if (!$user) {
            return;
        }

        $activities = [
            [
                'type' => 'created',
                'model' => 'App\Models\Teacher',
                'model_id' => 1,
                'title' => 'New Teacher Created',
                'description' => 'Maria Santos has been added as a new teacher',
                'icon' => 'fas fa-chalkboard-teacher',
                'user_id' => $user->id,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'type' => 'created',
                'model' => 'App\Models\Guardian',
                'model_id' => 1,
                'title' => 'New Parent/Guardian Created',
                'description' => 'John Smith has been added as a parent/guardian',
                'icon' => 'fas fa-user-friends',
                'user_id' => $user->id,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'type' => 'updated',
                'model' => 'App\Models\Teacher',
                'model_id' => 1,
                'title' => 'Teacher Information Updated',
                'description' => 'Teacher profile has been updated with new contact information',
                'icon' => 'fas fa-edit',
                'user_id' => $user->id,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'type' => 'created',
                'model' => 'App\Models\Student',
                'model_id' => 1,
                'title' => 'New Student Enrolled',
                'description' => 'Juan Dela Cruz has been enrolled in the system',
                'icon' => 'fas fa-graduation-cap',
                'user_id' => $user->id,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ];

        foreach ($activities as $activity) {
            \App\Models\Activity::create($activity);
        }
    }
}
