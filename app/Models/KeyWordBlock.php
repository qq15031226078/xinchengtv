<?php
namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeyWordBlock extends BaseModel{
	  use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'data_keyword_block';

    public function getKeywordBlockAll($where=[],$str='*')
	{
		return $this->where($where)->select($str)->get();
	}

	
}