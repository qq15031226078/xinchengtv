<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Geo extends BaseModel
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'sys_geo';
 
}
