<?php

use Illuminate\Support\Facades\Artisan;
use Database\Firebase\FirebaseSeeder;

Artisan::command('firebase:seed', function () {
    $seeder = app(FirebaseSeeder::class);
    $seeder->run();
    $this->info('Firebase seeding completed.');
})->purpose('Seed Firebase with roles, permissions, and Super Admin');
