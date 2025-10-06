<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Schedule::command('kehadiran:mark-absent')->weekdays()->dailyAt('13:01');
Schedule::command('kehadiran:auto-pulang')->dailyAt('19:01');