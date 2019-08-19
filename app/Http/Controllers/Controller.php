<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function responseFromat($statusCode = '501', $data = null, $message = null)
    {
        $messageItem = config('message');
        if(!array_key_exists($statusCode, $messageItem)) $statusCode = '501';
        if(empty($data)) $data = null;
        $errMsg = $message ? $message : $messageItem[$statusCode];
        if(empty($data)) $data = null;
        return [
            'statusCode' => $statusCode,
            'statusMessage' => $errMsg,
            'responseData' => $data
        ];
    } 
}
