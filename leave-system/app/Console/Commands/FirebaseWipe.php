<?php
namespace App\Console\Commands; 
use Illuminate\Console\Command;
use App\Services\FirebaseService;

class FirebaseWipe extends Command
{
    protected $signature = 'firebase:drop {node}';
    protected $description = 'Drop a node (like a table) in Firebase';

    public function handle(FirebaseService $firebase)
    {
        $node = $this->argument('node');

        if (!$this->confirm("Are you sure you want to DROP the '{$node}' node?")) {
            $this->info('Cancelled.');
            return;
        }

        $firebase->drop($node);
        $this->info("✅ '{$node}' node dropped from Firebase.");
    }
}
