<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // 🚀 TAMBAHKAN withSchedule DI SINI
    ->withSchedule(function (Schedule $schedule) {
        // Pindahkan logika penjadwalan Anda ke dalam Closure ini
        $schedule->command('kehadiran:mark-absent')->dailyAt('13:01')
            ->timezone('Asia/Jakarta');

        $schedule->command('kehadiran:auto-pulang')->dailyAt('19:01')
            ->timezone('Asia/Jakarta');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
