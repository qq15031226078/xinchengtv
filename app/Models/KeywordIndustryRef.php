<?php 

namespace App\Models;

use Illuminate\Support\Facades\DB;

class KeywordIndustryRef extends BaseModel
{
	protected $table = 'sys_keyword_industry_ref';

	/*  查询行业类型相关的 公司/机构/人物/投资公司 个数 */
    public function getKeywordIndustryCount($code,$type){
    	$count = $this->join('sys_industry', 'sys_keyword_industry_ref.industry_code', '=', 'sys_industry.code')
    				  ->join('sys_keyword', 'sys_keyword_industry_ref.keyword_code', '=', 'sys_keyword.code')
		              ->where(['sys_industry.code'=>$code])
		              ->where(['sys_keyword.keyword_category_code'=>$type])
		              ->count();
		return $count;
    }

    public function getKeywordIndustryCount_Children($code,$type){
    	$count = $this->join('sys_industry', 'sys_keyword_industry_ref.industry_code', '=', 'sys_industry.code')
    				  ->join('sys_keyword', 'sys_keyword_industry_ref.keyword_code', '=', 'sys_keyword.code')
		              ->where(['sys_industry.parent'=>$code])
		              ->where(['sys_keyword.keyword_category_code'=>$type])
		              ->count();
		return $count;
    }

    public function getMaxOrder() {
		return $this->max('order');
    }
    /*  查询行业类型相关的 公司/机构/人物/投资公司 个数 */
    public function getKeywordIndustryAll($where=[],$str='*'){
    	return $this->where($where)->select($str)->orderBy('order','asc')->orderBy('id','desc')->get();
    }
    /*  查询行业类型相关的 公司/机构/人物/投资公司 （单个） */
    public function getKeywordIndustryOne($where=[],$str='*')
    {
        return $this->where($where)->select($str)->first();
    }
    /* 统计 */
    public function getCount($where=[]){
        return $this->where($where)->count();
    }
}