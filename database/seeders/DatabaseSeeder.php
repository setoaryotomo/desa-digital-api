<?php

namespace Database\Seeders;

use App\Models\DevelopmentApplicant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            HeadOfFamilySeeder::class,
            SocialAssistanceSeeder::class,
            SocialAssistanceRecipientSeeder::class,
            EventSeeder::class,
            EventParticipantSeeder::class,
            DevelopmentSeeder::class,
            DevelopmentApplicantSeeder::class,
        ]);
    }
}