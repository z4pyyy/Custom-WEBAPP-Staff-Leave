<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class CheckPagePermissionMiddleware extends Command
{
    protected $signature = 'check:page-permission-middleware';
    protected $description = 'Check all named routes and detect which ones are missing the check.page.permission middleware';

    public function handle()
    {
        $routes = Route::getRoutes();

        $missing = [];

        foreach ($routes as $route) {
            $name = $route->getName();
            $middleware = $route->gatherMiddleware();

            if ($name && !in_array('check.page.permission', $middleware)) {
                $missing[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $name,
                ];
            }
        }

        if (count($missing) === 0) {
            $this->info('✅ All named routes have check.page.permission middleware.');
            return 0;
        }

        $this->warn('⚠️ 以下 Route 没有 check.page.permission middleware：');
        $this->table(['Method', 'URI', 'Route Name'], $missing);

        return 1;
    }
}
