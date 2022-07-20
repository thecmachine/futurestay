<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Users';

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
        $this->info("Get Users Script Begin");
        return 0;
    }
}
