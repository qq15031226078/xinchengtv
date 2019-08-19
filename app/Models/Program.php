<?php
 
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class Program extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'xc_program'; 
}
