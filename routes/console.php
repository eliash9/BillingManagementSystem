<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\GenerateDueInvoicesJob;
use App\Jobs\SendInvoiceReminderJob;
use App\Jobs\AutoSuspendServiceJob;

// Jadwal Generate Invoice harian
Schedule::job(new GenerateDueInvoicesJob)->dailyAt('00:00');

// Jadwal kirim reminder
Schedule::job(new SendInvoiceReminderJob)->dailyAt('08:00');

// Jadwal cek dan auto suspend server
Schedule::job(new AutoSuspendServiceJob)->dailyAt('01:00');
