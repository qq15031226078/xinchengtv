<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Management extends BaseModel
{
    use SoftDeletes;
    protected $table = 'xc_management';

    protected $dates = ['deleted_at'];

    protected  $fillable = ['id','keyword_code','executive','type_code_role','join_date','quit_date','type','stock','is_index'];
//    public $timestamps = false;

    public function updateManagement($where, $data){
        return $this->where($where)->update($data);
    }

    public function getManagementOne($where = [], $field = ['*']){
        return $this->where($where)->select($field)->first();
    }
    /*
     * 分页数据
     */
    public function getManagementList($where = [], $field = ['*'] , $limit=15){
        if($field==[]) $field=$this->fillable;
       return $this->where($where)->select($field)->paginate($limit);
    }

    public function getManagementInfo($where=[],$field = ['*']){
        return $this->where($where)->select($field)->orderBy('order')->get();
    }

    /* 统计 */
    public function getManagementCount($where=[]){
        return $this->where($where)->count();
    }

}