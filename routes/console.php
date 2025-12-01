<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the command to run every hour
Schedule::command('transfers:process-scheduled')->hourly();

// Schedule budget reset at the start of each month (1st day at 00:01)
Schedule::command('budgets:reset-monthly')->monthlyOn(1, '00:01');
