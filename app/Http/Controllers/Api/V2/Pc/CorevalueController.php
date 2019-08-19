<?php
namespace App\Http\Controllers\Api\V2\Pc;

use DB;
use Illuminate\Http\Request;

class CorevalueController extends CommonController
{
    public function __construct()
    {
        $this->res = [
            'code'     => '',
            'response' => [],
            'message'  => NULL
        ];
    }

}