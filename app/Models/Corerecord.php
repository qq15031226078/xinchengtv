<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Corerecord extends BaseModel
{
    use SoftDeletes;
    protected $table = 'data_core_record';
    protected $dates = ['deleted_at'];

    /* 添加 产科项信息条 */
    public function addCorerecord($data)
    {
        if(empty($data)) return false;
        return $this->insertGetId($data);
    }

    /* 修改 产科项信息条 */
    public function updateCorerecord($where,$data)
    {
        if(empty($where) || empty($data)) return false;
        return $this->where($where)->update($data);
    }

    /* 查询 全部 产科项信息条 */
    public function getCorerecordAll($where=[],$str='*',$page=1,$limit=1000)
    {
        $pages = ($page-1)*$limit;
        return $this->where($where)->select($str)->orderBy('order')->skip($pages)->take($limit)->get();
    }

    /* 查询 单条 产科项信息条 */
    public function getCorerecordOne($where=[],$str='*')
    {
        return $this->where($where)->select($str)->take(1)->first();
    }

    /* 删除 产科项信息条 */
    public function deleteCorerecord($where)
    {
        if(empty($where)) return false;
        return $this->where($where)->delete();
    }

    /* 查询 数量 */
    public function getCorerecordCount($where=[])
    {
        return $this->where($where)->count();
    }

}

