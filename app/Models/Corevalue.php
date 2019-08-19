<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Corevalue extends BaseModel
{
	use SoftDeletes;
	protected $table = 'xc_core_value';
	public $timestamps = false;
    protected $dates = ['deleted_at'];
	protected $fillable = ['type', 'name', 'name_en', 'keyword_code	', 'industry_code', 'publish_date', 'close_date', 'type', 'news_id', 'thumb_path', 'author', 'auditor', 'reason', 'is_passed', 'is_published', 'is_disabled', 'is_locked', 'order'];

	/* 添加产科项 */
	public function addCorevalue($data)
	{
		if(empty($data)) return false;
		return $this->insertGetId($data);
	}

	/* 修改产科项 */
	public function updateCorevalue($where,$data)
	{
		if(empty($where) || empty($data)) return false;
		return $this->where($where)->update($data);
	}

	/* 查询 全部 产科项 */
	public function getCorevalueAll($where=[],$str='*',$page=1,$limit=1000)
	{
		$pages = ($page-1)*$limit;
		return $this->where($where)->select($str)->orderBy('id','desc')->orderBy('order')->skip($pages)->take($limit)->get();
	}
	public function getCorevalueAllin($where=[],$str='*')
	{
		return $this->whereIn('id',$where)->select($str)->get();
	}


	/* 查询 单条 产科项 */
	public function getCorevalueOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->first();
	}

	/* 删除产科项 */
	public function deleteCorevalue($where)
	{
		if(empty($where)) return false;
		return $this->where($where)->delete();
	}

	/* 统计 */
	public function getCorevalueCount($where=[])
	{
		return $this->where($where)->count();
	}

	public function getGalleryKeywordInfo($where = [], $str = 'xc_core_value.*')
    {
        return $this->where($where)
            ->select($str)
            ->leftjoin('sys_keyword', 'xc_core_value.keyword_code', '=', 'sys_keyword.code')
            ->get();
    }

}

