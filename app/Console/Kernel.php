<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\SendEmailsReminderVencido::class,
        Commands\SendEmailsReminderVence3::class,
        Commands\SendEmailsReminderVence24::class,
        Commands\alertas::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sendMailVencidos')
            ->everyMinute()
            ->appendOutputTo("ticketsvencidos.txt");

        $schedule->command('sendMailVence3')
            ->everyMinute()
            ->appendOutputTo("ticketsvence3.txt");
            
        $schedule->command('sendMailVence24')
            ->everyMinute()
            ->appendOutputTo("ticketsvence24.txt");

        $schedule->command('send:Alertas')
            ->everyMinute()
            ->appendOutputTo("alertas.txt");

        $schedule->command('backup:clean')->daily();
        $schedule->command('backup:run')->daily();

    }
}
