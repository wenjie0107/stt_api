<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class Helper
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public static function postData($url,$data,$header)
    {
        try
        {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            // if (is_array($data))
            // {
            //     $data = json_encode($data);
            // }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            
            $response = curl_exec($ch);
            // Check if any error occurred during the request
            if(curl_errno($ch)){
                echo 'Request Error:' . curl_error($ch);
            }
            curl_close($ch);
            
            return $response;
        }
        catch(\Exception $e)
        {
            return '';
        }
    }
}