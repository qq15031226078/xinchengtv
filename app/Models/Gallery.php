<?php 

namespace App\Models;

// use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends BaseModel
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	protected $table = 'xc_gallery';


	/* 查询 全部 图库 */
	public function getGalleryAll($where=[],$str='*')
	{
		return $this->where($where)->select($str)->orderBy('order','asc')->orderBy('created_at','asc')->get();
	}

	/* 查询 单条 图库 */
	public function getGalleryOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->first();
	}

	/*  通过 内容总表 查询 图库 信息*/
    public function getGalleryOneInfo($where=[],$str='*'){
    	$count = $this->join('xc_content', 'xc_gallery.id', '=', 'xc_content.video_id')
		              ->where($where)
		              ->select($str)
		              ->first();
		return $count;
    }

    // 统计
    public function getGalleryCount($where=[]){
    	return $this->where($where)->count();
    }

    public function getGalleryInfo($where = [], $str = '*')
    {
        return $this->where($where)
            ->select($str)
            ->leftjoin('sys_keyword', 'xc_gallery.keyword_code', '=', 'sys_keyword.code')
            ->leftjoin('sys_industry', 'xc_gallery.industry_code', '=', 'sys_industry.code')
            ->first();
    }
}