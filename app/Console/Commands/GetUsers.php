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
    protected $signature = 'command:getusers {limit=10}';

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

        $limit = $this->argument('limit');

        $service_url     = 'https://randomuser.me/api/';
        $curl            = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response   = curl_exec($curl);
        curl_close($curl);
        $json_response    = json_decode($curl_response);
        // $quotes          = $json_objekat->contents->quotes;
        // foreach($quotes as $intKey=>$objQuote){
        //     echo $objQuote->title       . '<br>';
        //     echo $objQuote->author      . '<br>';
        //     echo $objQuote->quote       . '<br>';
        //     echo $objQuote->background  . '<br>';
        // }

        $this->info(print_r($json_response->results[0]));
        $this->newLine(2);
        $this->info($json_response->results[0]->name->first);
        $this->info($json_response->results[0]->name->last);
        $this->info($json_response->results[0]->phone);
        $this->info($json_response->results[0]->email);
        $this->info($json_response->results[0]->location->country);

        $this->newLine();
        $this->info("Limit: " . $limit);


        return 0;
    }
}
