<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// White-Label Daily Expiry & Storage Alerts Evaluator
// 1-Month Plan => Alert 7 days before | 6-Month Plan => Alert 15 days before | Yearly Plan => Alert 30 days before
Schedule::command('agencies:check-expiry-alerts')->dailyAt('09:00')->withoutOverlapping();

