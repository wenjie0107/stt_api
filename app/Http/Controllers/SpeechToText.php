<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Helper;
use Illuminate\Support\Facades\Http;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Log;

class SpeechToText extends Controller
{
    public static function sttApi(Request $request)
    {
       Log::Debug($request);
       
        
       
        // Path to the directory you want to save the files
        $target_dir = (new TemporaryDirectory(public_path()))->create();
        // $target_dir->name('tmp')->force()->create();

        // Check if file is uploaded
        if(isset($_FILES["audio"]["name"])) {
            // Prepare the file path
            $target_file = $target_dir->path($_FILES["audio"]["name"]);

            // Move the uploaded file to your target directory
            if (move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file)) {
                //echo "The file ". basename( $_FILES["audio"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit();
            }

            $url = ENV('OPENAI_URL');
            // Prepare the headers
            $headers = array(
                'Authorization: Bearer ' .ENV('OPENAI_API_KEY')
            );

            // Create a CURLFile object / preparing the image for upload
            // $cfile = new \CURLFile($target_dir->path(),'webm',$_FILES["audio"]["name"]);
            $cfile = new \CURLFile($target_file);
            
            // Prepare the request body with the file and model
            $data = [
                'file' => $cfile,
                'model' => 'whisper-1',
            ];

            $request = Helper::postData($url,$data,$headers);
            Log::Debug($request);
            $data = json_decode($request,true);
        
            return $data;
        } else {
            echo "No file was uploaded.";
        }
    }
}