<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Database\Seeders\ActualizacionPermisosSeeder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ini_set('memory_limit', '15360M');

        try {
            if (!app()->runningInConsole()) {

                $lockPath = storage_path('framework/permisos_seeded.lock');

                if (!file_exists($lockPath)) {
                    if (DB::connection()->getPdo()) {
                        file_put_contents($lockPath, now());
                        Log::info('Running ActualizacionPermisosSeeder from AppServiceProvider');
                        Artisan::call('db:seed', [
                            '--class' => ActualizacionPermisosSeeder::class,
                            '--force' => true,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
        }

        User::observe(UserObserver::class);
    }
}