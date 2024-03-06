<?php

namespace App\Http\Controllers\ViewControllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SpeechToText;
use Illuminate\Http\Request;
use Log;

class SpeechToTextViewController extends Controller
{
    public function sttApi(Request $request)
    {
        $data = SpeechToText::sttApi($request);

        return $data;
    }
}