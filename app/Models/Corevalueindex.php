<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Corevalueindex extends BaseModel
{
	protected $table = 'xc_core_value_index';

	/* 添加 行业指标表 */
	public function addCorevalueindex($data)
	{
		if(empty($data)) return false;
		return $this->insertGetId($data);
	}

	/* 修改 行业指标表 */
	public function updateCorevalueindex($where,$data)
	{
		if(empty($where) || empty($data)) return false;
		return $this->where($where)->update($data);
	}

	/* 查询 全部 行业指标表 */
	public function getCorevalueindexAll($where=[],$str='*',$page=1,$limit=1000)
	{
		$pages = ($page-1)*$limit;
		return $this->where($where)->select($str)->orderBy('id','desc')->orderBy('order')->skip($pages)->take($limit)->get();
	}
	public function getCorevalueindexAllin($where=[],$str='*')
	{
		return $this->whereIn('industry_code',$where)->select($str)->get();
	}

	/* 查询 单条 行业指标表 */
	public function getCorevalueindexOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->take(1)->first();
	}

	/* 删除行业指标表 */
	public function deleteCorevalueindex($where)
	{
		if(empty($where)) return false;
		return $this->where($where)->delete();
	}

	/* 行业指标表 数量 */
	public function getCorevalueindexCount($where=[])
	{
		return $this->where($where)->count();
	}

}

