<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Response;

class UserXmlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($limit = 10)
    {

        // $limit = $this->argument('limit');
        $users = $this->getUsers($limit);
        $sorted_users = $this->sortUsers($users);
        $usersXML = $this->getXML($sorted_users);

        $response = \Response::make($usersXML, 200);
        $response->header('Content-Type', 'text/plain');
        return $response;
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
