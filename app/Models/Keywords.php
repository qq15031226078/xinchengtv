<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Keywords extends BaseModel{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'sys_keyword';

    public function getKeywordsOne($where=[] ,$orWhere = [] ,$field=['*']){
        return $this->where($where)->orWhere($orWhere)->select($field)->first();
    }

    public function getMaxOrder(){
    	return $this->max('order');
    } 
    
	public function getKeywordAll($where=[],$str='*')
	{
		return $this->where($where)->select($str)->get();
	} 
	public function getKeywordOne($where=[],$str='*')
	{
		return $this->where($where)->select($str)->first();
	} 


	public function getKeywordCount($where=[])
	{
		return $this->where($where)->count();
	}

	/* 关键词信息：包括城市 （想要部分字段，请从下面那一大堆字段中复制）*/
	public function getKeywordOneinfo($where=[], $str=[])
	{
		$arr = empty($str) ? ['sys_keyword.id','sys_keyword.name','name_en','sys_keyword.code as keyword_code','keyword_category_code','oneword','desc','geo_code_country','sys_geoa.name as country_name','geo_code_province','sys_geob.name as province_name','geo_code_city','sys_geoc.name as city_name','birth','logo_path','head_path','sys_keyword.created_at'] : $str;
		return $this
				->leftJoin('sys_geo as sys_geoa','sys_keyword.geo_code_country','=','sys_geoa.code')
				->leftJoin('sys_geo as sys_geob','sys_keyword.geo_code_province','=','sys_geob.code')
				->leftJoin('sys_geo as sys_geoc','sys_keyword.geo_code_city','=','sys_geoc.code')
				->where($where)
				->select($arr)
				->first();
	}
}