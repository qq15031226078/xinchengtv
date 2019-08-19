<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Picture extends BaseModel
{
    protected $table = 'xc_picture';

    /* 查询 单个 图片 */
    public function getPictureOne($where = [], $str = '*')
    {
        $info = $this->where($where)->select($str)->take(1)->first();
        return $info;
    }


    public function getAllPicture($where = [], $select = '*')
    {
        return $this->where($where)->select($select)->orderBy('order','asc')->orderBy('created_at','asc')->get();
    }

    // 统计
    public function getPictureCount($where=[]){
    	return $this->where($where)->count();
    }

}

