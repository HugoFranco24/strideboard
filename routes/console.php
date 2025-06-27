<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\CheckLateNotifications;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Project;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    (new CheckLateNotifications())->handle();
})->everyMinute();