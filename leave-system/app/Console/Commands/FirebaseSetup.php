<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use Database\Firebase\FirebaseMigration;
use Database\Firebase\FirebaseSeeder;
use Database\Firebase\FirebaseRolesSeeder;
use Database\Firebase\FirebaseSAdminSeeder;

class FirebaseSetup extends Command
{
    protected $signature = 'firebase:setup';
    protected $description = 'Run Firebase migration and seed data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(FirebaseService $firebase)
    {
        $this->info('🔧 Running Firebase Migration...');
        (new FirebaseMigration($firebase))->run();

        $this->info('🌱 Running Firebase Seeder...');
        (new FirebaseSeeder($firebase))->run();

        $this->info('✅ Firebase is ready!');
    }
}
