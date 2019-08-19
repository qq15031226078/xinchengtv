<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends BaseModel
{
	use SoftDeletes;

	protected $table = 'sys_industry';
	
	protected $dates = ['deleted_at'];

	/* 查询 全部 行业类型 */
	public function getIndustryAll($where=[],$str='*')
	{
		return $this->where($where)->select($str)->orderBy('id','desc')->get();
	}

	/* 查询 单条 产科项 */
	public function getIndustryOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->first();
	}
	/* 修改 单个 行业类型 */
	public function updateIndustry($where,$data)
    {
        if(empty($where) || empty($data)) return false;
        $id = $this->where($where)->update($data);
        return $id;
    }

    /* 查询 同级 行业类型 个数*/
	public function getIndustryCount($where=[])
	{
		return $this->where($where)->count();
	}

	/* 查询 全部 行业类型 (数组) */
	public function getIndustryAllArray($where=[],$str='*')
	{
		return $this->where($where)->select($str)->orderBy('order','asc')->get()->toArray();
	}
}
