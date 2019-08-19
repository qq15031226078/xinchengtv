<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Tv extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'xc_tv';
 

}