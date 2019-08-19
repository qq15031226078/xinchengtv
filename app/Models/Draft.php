<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
class Draft extends BaseModel
{
	protected $connection = 'db_writing';
    protected $table = "draft"; 
}