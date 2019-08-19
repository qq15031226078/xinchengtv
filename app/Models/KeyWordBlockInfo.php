<?php
namespace App\Models;

class KeyWordBlockInfo extends BaseModel{
	 
    protected $dates = ['deleted_at'];
    protected $table = 'data_keyword_block_info';

    public function getKeyWordBlockContents($where=[],$str)
	{
		return $this->where($where)->orderBy('order','asc')->orderBy('created_at','asc')->pluck($str);
	}
}