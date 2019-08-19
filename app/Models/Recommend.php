<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
class Recommend extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'xc_recommend';
 
}