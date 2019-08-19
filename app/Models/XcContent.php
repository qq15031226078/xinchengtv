<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class XcContent extends BaseModel
{
	protected $table = 'xc_content';

	public function addContent($data){
		if(empty($data)){
			return false;
		} 
		return $this->insertGetId($data);
	}

	public function getMaxOrder(){
		return $this->max('order');
	}



    /**
     * todo 获取视频相关对象
     * @param $where
     * */
    public function getDataRelate($where){
        return $this::leftJoin('sys_keyword','xc_content.keyword_code','=','sys_keyword.code')
            ->leftJoin('xc_core_value', function ($join) {
                $join->on('xc_content.project_id', '=', 'xc_core_value.id')
                    ->orOn('xc_content.product_id', '=', 'xc_core_value.id')
                    ->orOn('xc_content.tech_id', '=', 'xc_core_value.id');
            })
            ->leftJoin('sys_industry','xc_content.industry_code','=','sys_industry.code')
            ->where($where)
            ->select(
                'xc_content.id',
                'xc_content.keyword_code',
                'xc_content.industry_code',
                'xc_content.project_id',
                'xc_content.product_id',
                'xc_content.tech_id',
                'xc_content.position',
                'sys_keyword.name as keyword_name',
                'xc_core_value.name as core_name',
                'sys_industry.name as industry_name'
            )
            ->get()
            ->toArray();
    }



    /**
     * todo 获取视频相关关键词
     * @param $where
     * */
    public function getVideoKeyword($where){
        return $this::leftJoin('sys_keyword', 'xc_content.keyword_code', '=', 'sys_keyword.code')
            ->leftJoin('sys_keyword_category', 'sys_keyword_category.code', '=', 'sys_keyword.keyword_category_code')
            ->where($where)
            ->select(
                'xc_content.id',
                'xc_content.keyword_code',
                'sys_keyword.name as keyword_name',
                'xc_content.order',
                'sys_keyword_category.name_en as keyword_type_name'
            )
            ->get();
    }



    /**
     * todo 获取视频相关行业
     * @param $where
     * */
    public function getVideoIndustry($where){
        return $this::leftjoin('sys_industry', 'xc_content.industry_code', '=', 'sys_industry.code')
            ->where($where)
            ->select(
                'xc_content.id',
                'xc_content.industry_code',
                'sys_industry.name as industry_name',
                'xc_content.order'
            )
            ->get();
    }



    /**
     * todo 获取视频相关的产科项
     * @param $where
     * */
    public function getCoreValue($where){
        return $this::leftJoin('xc_core_value', function ($join) {
                $join->on('xc_content.project_id', '=', 'xc_core_value.id')
                    ->orOn('xc_content.product_id', '=', 'xc_core_value.id')
                    ->orOn('xc_content.tech_id', '=', 'xc_core_value.id');
            })
            ->where($where)
            ->where(function ($query) {
                $query->where('xc_content.project_id','<>',0)
                    ->orWhere('xc_content.product_id','<>',0)
                    ->orWhere('xc_content.tech_id','<>',0);
            })
            ->select(
                'xc_content.id',
                'xc_core_value.id as core_id',
                'xc_core_value.name',
                'xc_core_value.type',
                'xc_content.order'
            )
            ->get();
    }




    /* 查询 全部 内容总表 */
	public function getContentAll($where=[],$str='*')
	{
		return $this->where($where)->select($str)->orderBy('order','asc')->get();
	}

	/* 查询 单条 内容总表 */
	public function getContentOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->first();
	}

	/* 修改 单个 内容总表 */
	public function updateContent($where,$data)
    {
        if(empty($where) || empty($data)) return false;
        $id = $this->where($where)->update($data);
        return $id;
    }

    /* 查询 同级 内容总表 个数*/
	public function getContentCount($where=[])
	{
		return $this->where($where)->count();
	}

	/* 查询 内容总表 列表 */
	public function getContentList($where=[],$limit,$str='*')
	{
		return $this->groupBy('news_id')
                    ->groupBy('video_id')
                    ->groupBy('gallery_id')
                    ->where($where)
					->select($str)
					->orderBy('order','asc')
					->orderBy('id','desc')
					->paginate($limit);
	}
}