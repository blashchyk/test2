<?php

namespace App\Console;

use App\Console\Commands\ParsingTopics;
use App\Console\Commands\ReindexCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ParsingTopics::class,
        ReindexCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('parse_topics')->daily();
        $schedule->command('search:reindex')->daily();
    }
}
