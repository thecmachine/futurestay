<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \stdClass;
use \DOMDocument;

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
        // $this->info("Get Users Script Begin");

        $limit = $this->argument('limit');
        $users = $this->getUsers($limit);
        $sorted_users = $this->sortUsers($users);
        $usersXML = $this->getXML($sorted_users);

        $this->info($usersXML);

        return $usersXML;
    }

    public function array2xml($data, $name='root', &$doc=null, &$node=null){
        if ($doc==null){
            $doc = new \DOMDocument('1.0','UTF-8');
            $doc->formatOutput = TRUE;
            $node = $doc;
        }
        
        try {
            if (is_array($data)){
                foreach($data as $var=>$val){
    
                    if (is_numeric($var)){
                        $this->array2xml($val, $name, $doc, $node);
                    }else{
                        if (!isset($child)){
                            $child = $doc->createElement($name);
                            $node->appendChild($child);
                        }
                            $labels = ['root', 'first', 'last', 'phone', 'email', 'country'];
                            if(!in_array($var, $labels)){
                                $var = 'user';
                            }
        
                        $this->array2xml($val, $var, $doc, $child);
                    }
    
                }
            }else{
                $child = $doc->createElement($name);
                $node->appendChild($child);
                $textNode = $doc->createTextNode($data);
                $child->appendChild($textNode);
            }
        } catch (Exception $e) {
            $child = $doc->createElement('error');
            $node->appendChild($child);
            $textNode = $doc->createTextNode('xml cannot be parsed from these names as a valid string');
            $child->appendChild($textNode);
        }
        
    
        if ($doc==$node) return $doc->saveXML();
        return $doc;
    }//array2xml

    public function getXML($sorted_users)
    {
        $xml = $this->array2xml($sorted_users);
        return $xml;
    }

    public function sortUsers($users){
        krsort($users);
        $sorted_users = $users;
        return $users;
    }

    public function getUsers($limit){
        
        $users = [];
        for($i = 0; $i < $limit; $i++)
        {
            $user_response = $this->curlUsers();
            $user = [];
            $user['first'] = $user_response->results[0]->name->first;
            $user['last'] = $user_response->results[0]->name->last;
            $user['phone'] = $user_response->results[0]->phone;
            $user['email'] = $user_response->results[0]->email;
            $user['country'] = $user_response->results[0]->location->country;

            $users[$user['last']] =  $user; 
        }

        return $users;
    }

    public function curlUsers()
    {
        $service_url     = 'https://randomuser.me/api/';
        $curl            = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response   = curl_exec($curl);
        curl_close($curl);
        $json_response    = json_decode($curl_response);
        return $json_response;
    }
}
