<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Strongswan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strongswan:reboot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to restart the Strongswan tunnel when its down.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $exec = "echo Astro@W!F1 | /usr/bin/sudo -S strongswan start";
        Log::info(print_r(shell_exec($exec)));

        return 0;
    }
}
