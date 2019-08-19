<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends BaseModel
{
	use SoftDeletes;

	protected $table = 'xc_video';

	protected $dates = ['deleted_at'];

	/* 查询 全部 视频 */
	public function getVideoAll($where=[],$str='*')
	{
		return $this->where($where)->select($str)->orderBy('order','asc')->get();
	}

	/* 查询 单条 视频 */
	public function getVideoOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->first();
	}


	/**
     * todo 获取详细信息
     * @param $where
	 * */
	public function getDataInfo($where){
        return $this::leftJoin('sys_keyword','xc_video.keyword_code','=','sys_keyword.code')
            ->leftJoin('sys_industry','xc_video.industry_code','=','sys_industry.code')
            ->leftJoin('xc_news','xc_video.news_id','=','xc_news.id')
            ->where($where)
            ->select(
                'xc_video.*',
                'sys_keyword.name as keyword_name',
                'sys_industry.name as industry_name',
                'sys_industry.parent as industry_parent_code',
                'xc_news.title as new_title'
            )
            ->get()
            ->toArray();
    }


    /* 查询视频列表 */
	public function getVideoList($where=[],$limit,$str='*')
	{
		return $this->where($where)->select($str)->orderBy('order','asc')->orderBy('id','desc')->paginate($limit);
	}


	/* 修改 单个 内容总表 */
	public function updateVideo($where,$data)
    {
        if(empty($where) || empty($data)) return false;
        $id = $this->where($where)->update($data);
        return $id;
    }


    /*  通过 内容总表 查询 视频 信息*/
    public function getVideoOneInfo($where=[],$str='*'){
    	$count = $this->join('xc_content', 'xc_video.id', '=', 'xc_content.video_id')
		              ->where($where)
		              ->select($str)
		              ->first();
		return $count;
    }
    // 统计
    public function getVideoCount($where=[]){
    	return $this->where($where)->count();
    }
}