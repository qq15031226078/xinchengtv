<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
class DraftBody extends BaseModel
{
    protected $table = "draft_body"; 
}