<?php
namespace App\Http\Controllers\Api\V2\Pc;

use App\Models\Gallery;
use App\Models\Picture;
use App\Models\Corevalue;
use App\Models\Keywords;
use App\Models\KeyWordBlock;
use App\Models\KeyWordBlockInfo;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Api\V2;
use DB;

/**
 * 公共逻辑处理层
 */
class CommonController extends Controller
{
	protected $gallery;
	protected $picture;
	protected $corevalue;
	protected $keywords;
	protected $keywordblock;
	protected $keywordblockinfo;

	public function getObject(){
  		$this->gallery = new Gallery();
  		$this->picture = new Picture();
  		$this->corevalue = new Corevalue();
  		$this->keywords = new Keywords();
  		$this->keywordblock = new KeyWordBlock();
  		$this->keywordblockinfo = new KeyWordBlockInfo();
	}

    /**
     * 关键词职务(PING)
     */
    public function keywordRole($code){
        $category = (DB::table('sys_keyword')->select(['keyword_category_code as category'])->where(['code'=>$code,'deleted_at'=>NULL])->first());
        if(empty($category) OR $category->category != 'KC003')  return null;
        $data = DB::table('xc_management as m')->where(['executive'=>$code,'m.deleted_at'=>NULL])
            ->leftJoin('sys_type as t','m.type_code_role','=','t.code')
            ->leftJoin('sys_keyword as k','m.keyword_code','=','k.code')
            ->select(['k.name as company','t.name as role'])
            ->first();
        if(empty($data)) return null;
        return collect($data)->toArray();
    }

    /**
     * 通过 code 查出行业类型信息(PING)
     */
    public function industryFullname($code){
        $industry = [];
        $industryinfo = DB::table('sys_industry')->select(['name','parent'])->where(['code'=>$code,'deleted_at'=>NULL])->first();
        if(empty(collect($industryinfo)))    return FALSE;
        if(empty($industryinfo->parent)){
            $industry['industry_fullname'] = $industryinfo->name;
        }else{
            $industry_one    = DB::table('sys_industry')->select(['name'])->where(['code'=>$industryinfo->parent,'deleted_at'=>NULL])->first()->name;
            $industry_second = $industryinfo->name;
            $industry['industry_fullname'] = $industry_one.' / '.$industry_second;
        }
        return $industry;
    }
    /**
     * 根据关键词 code 获取关键词基本信息
     */
    public function keywordInfo($code){
        $keywordinfo = DB::table('sys_keyword')
            ->select(['code','name as name_cn','name_en','keyword_category_code as category_code','oneword','logo_path','head_path','desc'])
            ->where(['code'=>$code,'deleted_at'=>NULL])
            ->first();
        if(empty($keywordinfo))  return FALSE;
        $keywordinfo->birth_info = $this->keywordFound($code);
        return collect($keywordinfo)->toArray();
    }
    /**
     * 关键词创立(PING)
     */
    public function keywordFound($code){
        $keywordinfo = DB::table('sys_keyword')->select(['geo_code_province','geo_code_city','birth','keyword_category_code'])->where(['code'=>$code,'deleted_at'=>NULL])->first();
        if(empty($keywordinfo)) return FALSE;
        switch ($keywordinfo->keyword_category_code) {
            case 'KC003':
                $flag = '生于：';
                break;
            case '':
                $flag = '';
                break;
            default:
                $flag = '创立于：';
                break;
        }
        $array = collect($keywordinfo)->toArray();
        $data = $this->regionFullname($array['geo_code_province'],$array['geo_code_city']);
        $birth = explode('-',$array['birth']);
        $birth[0] = $birth[0] == '0000' ? '' : $birth[0];
        $birth[1] = $birth[1] == '00' ? '' : $birth[1];
        $birth[2] = $birth[2] == '00' ? '' : $birth[2];
        if(!empty($data)){
            if($data['city'] != ''){
                $str1 = $data['guo'].$data['sheng'].$data['city'];
            }else{
                $str1 = $data['guo'].$data['sheng'];
            }
        }else{
            $str1 = '';
        }
        if($birth[0] != '' && $birth[1] == '' && $birth[2] == ''){
            $str2 = $birth[0].'年';
        }elseif($birth[0] != '' && $birth[1] != '' && $birth[2] == ''){
            $str2 = $birth[0].'年'.$birth[1].'月';
        }elseif($birth[0] != '' && $birth[1] != '' && $birth[2] != ''){
            $str2 = $birth[0].'年'.$birth[1].'月'.$birth['2'].'日';
        }else{
            $str2 = '';
        }
        if($str1 != '' && $str2!=''){
            $str = $str1.','.$flag.$str2;
        }elseif($str1 != '' && $str2 == ''){
            $str = $str1;
        }elseif($str1 == '' && $str2 != ''){
            $str = $flag.$str2;
        }else{
            $str = '';
        }
        return $str;

    }
    /**
     * 查询出关键词的国家省市区(PING)
     */
    public function regionFullname($geo_code_province='',$geo_code_city=''){
        if(!empty($geo_code_province)){
            $data = [];
            $region1 = DB::table('sys_geo')->select(['name','parent'])->where(['code'=>$geo_code_province,'deleted_at'=>NULL])->first();
            if(!empty($region1->parent)){
                $data['city']  = "";
                $data['sheng'] = $region1->name;
                $data['guo'] = DB::table('sys_geo')->select(['name as guo'])->where(['code'=>$region1->parent,'deleted_at'=>NULL])->first()->guo;
            }else{
                $data = NULL;
            }
        }elseif(!empty($geo_code_city)){
            $data = [];
            $region1 = DB::table('sys_geo')->select(['name','parent'])->where(['code'=>$geo_code_city,'deleted_at'=>NULL])->first();
            $data['city'] = $region1->name;
            if(!empty($region1->parent)){
                $region2 = DB::table('sys_geo')->select(['name as sheng','parent'])->where(['code'=>$region1->parent,'deleted_at'=>NULL])->first();
                if(!empty($region2->parent)){
                    $data['sheng'] = $region2->sheng;
                    $data['guo'] = DB::table('sys_geo')->select(['name as guo'])->where(['code'=>$region2->parent,'deleted_at'=>NULL])->first()->guo;
                }else{
                    $data = NULL;
                }
            }
        }else{
            $data = NULL;
        }
        return $data;
    }

    //图库图片信息 ———— 朱朔男
    public function galleryInfo($gallery_id){
		$this->getObject();
		$gallery = $this->gallery->getGalleryOne(['id'=>$gallery_id],['id','title','keyword_code','industry_code']);
        $gallery->pictures = $this->picture->getAllPicture(['gallery_id'=>$gallery->id],['desc','thumb_path','original_path','is_cover']);
        $gallery->num = $this->picture->getPictureCount(['gallery_id'=>$gallery->id]);
        return $gallery;
    }

    //产科项信息 ———— 朱朔男
    public function coreInfo($core_id){
    	$this->getObject();
        $coreinfo = $this->corevalue->getGalleryKeywordInfo(['xc_core_value.id'=>$core_id],['xc_core_value.id as core_id','xc_core_value.name as core_name','xc_core_value.name_en as core_name_en','xc_core_value.thumb_path as core_thumb_path','sys_keyword.code as keyword_code','sys_keyword.name as sys_keyword_name','sys_keyword.name_en as sys_keyword_name_en','sys_keyword.logo_path as keyword_logo_path'])->toArray();
        $data['coreInfo'] = $coreinfo[0];
        $data['coreInfo']['keyword_name'] = empty($data['coreInfo']['sys_keyword_name']) ? $data['coreInfo']['sys_keyword_name_en'] : $data['coreInfo']['sys_keyword_name'];
        unset($data['coreInfo']['sys_keyword_name']);
        unset($data['coreInfo']['sys_keyword_name_en']);
        return $data;
    }

    //关键词信息块 ———— 朱朔男
    public function blockInfo($keyword_code){
    	$this->getObject();
        $data = $this->keywordblock->getKeywordBlockAll(['keyword_code'=>$keyword_code],['id','title']);
        foreach ($data as $v) {
        	$v->contents = $this->keywordblockinfo->getKeyWordBlockContents(['block_id'=>$v->id],'content');
        }
        return $data;
    }

    // 将新闻概要转换成字符串-----------王磊
    public function transformation($array) {
        $res = '';
        $zi = ['；','。','，'];
        foreach($array as $key => $val) {
            $str = substr(trim($val), -3);
            if(in_array($str, $zi)) {
                $res .= substr(trim($val),0,strlen(trim($val))-3).'；';
            } else {
                $res .= $val.'；';
            }
        }
        return substr($res,0,strlen($res)-3);
    }
    public function transformationtwo($string) {
        if(empty($string)) return null;
        $arr = [ 120, 180, 240 ];
        $res = mb_substr( $string, 0, $arr[rand(0,2)], 'utf-8' );
        if($res) {
            return $res.'……';
        } else {
            return null;
        }
    }

    // 新闻概要-----------王磊
    public function draftInfo($draftid) {
        if(empty($draftid)) return null;
        $data = [];
        $keywordcode = DB::table('db_writing.draft_keyword')->where(['draft_id'=>$draftid, 'deleted_at'=>null])->orderBy('draft_keyword.order','asc')->select(['keyword_code'])->first()->keyword_code;
        if($keywordcode) {
            $data['keyword_code'] = $keywordcode;
        }
        $draftdata = DB::table('db_writing.draft')->where(['id'=>$draftid, 'deleted_at'=>null])->select(['id as draft_id','title','subtitle'])->first();
        $draftimg = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'picture', 'draft_body.deleted_at'=>null])->leftJoin('xc_picture','draft_body.element_id','=','xc_picture.id')->select(['thumb_path'])->limit(3)->get();
        if($draftdata) {
            $data['draft_id'] = $draftdata->draft_id;
            $data['title'] = $draftdata->title;
            $data['subtitle'] = $draftdata->subtitle;
            if(count($draftimg) > 0) {
                foreach($draftimg as $key => $va) {
                    $data['imgs'][] = $va->thumb_path; 
                }
            }
        }
        $draftgallery = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'gallery', 'draft_body.deleted_at'=>null])->leftJoin('xc_gallery','draft_body.element_id','=','xc_gallery.id')->select(['xc_gallery.id','title'])->limit(1)->first();
        if(count($draftgallery) > 0) {
            $data['gallerys'] = $draftgallery;
            $data['gallerys']->picture = DB::table('xc_picture')->where(['gallery_id'=>$draftgallery->id, 'deleted_at'=>null])->select(['thumb_path','desc'])->get();
        }

        $draftintro = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'text', 'deleted_at'=>null])->limit(3)->select(['element_content'])->get();
        if(count($draftintro) > 0) {
            $draftintros = '';
            foreach($draftintro as $val) {
                $draftintros .= $val->element_content;
            }
            $data['intro'] = $this->transformationtwo($draftintros);
        } else {
            $data['intro'] = null;
        }
        // $draftintro = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'intro'])->select(['element_content'])->get();
        // if(count($draftintro) > 0) {
        //     foreach($draftintro as $val) {
        //         $draftintros[] = $val->element_content;
        //     }
        //     $data['intro'] = $draftintros;
        // } else {
        //     $data['intro'] = null;
        // }
        return $data;
    }

    public function draftbody($draftid) {
        if(empty($draftid)) return null;
        $data = [];
        $keywordcode = DB::table('db_writing.draft_keyword')->where('draft_id',$draftid)->orderBy('draft_keyword.order','asc')->select(['keyword_code'])->first()->keyword_code;
        if($keywordcode) {
            $data['keyword_code'] = $keywordcode;
        }
        $draftdata = DB::table('db_writing.draft')->where('id',$draftid)->select(['id as draft_id','title','subtitle'])->first();
        $draftimg = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'picture'])->leftJoin('xc_picture','draft_body.element_id','=','xc_picture.id')->select(['thumb_path'])->limit(3)->get();
        if($draftdata) {
            $data['draft_id'] = $draftdata->draft_id;
            $data['title'] = $draftdata->title;
            $data['subtitle'] = $draftdata->subtitle;
            if(count($draftimg) > 0) {
                foreach($draftimg as $key => $va) {
                    $data['imgs'][] = $va->thumb_path; 
                }
            }
        }
        $draftgallery = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'gallery'])->leftJoin('xc_gallery','draft_body.element_id','=','xc_gallery.id')->select(['xc_gallery.id','title'])->limit(1)->get();
        if(count($draftgallery) > 0) {
            $data['gallerys'] = $draftgallery;
            $data['gallerys']['picture'] = DB::table('xc_picture')->where('gallery_id',$draftgallery->id)->select(['thumb_path'])->get();
        }
        $draftintro = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'intro'])->select(['element_content'])->get();
        if(count($draftintro) > 0) {
            foreach($draftintro as $val) {
                $draftintros[] = $val->element_content;
            }
            $data['intro'] = $draftintros;
        } else {
            $data['intro'] = null;
        }
        return $data;
    }

    // 关键词code (第一个参数关键词code, 第二个参数 显示图库个数)
    public function keywordGallerys($code,$limit=2)
    {
        if(empty($code)) return null;
        $data = DB::table('xc_gallery')->where('keyword_code',$code)->limit($limit)->select(['id','title'])->get();
        foreach($data as $key => $va) {
            $picture = DB::table('xc_picture')->where('gallery_id',$va->id)->get(['desc','thumb_path','original_path']);
            $data[$key]->picture = $picture;
        }
        return $data;
    }

    // 关键词侧边栏 信息
    public function keyinfo($code)
    {
        $keyinfo = DB::table('sys_keyword')
                ->leftJoin('sys_geo as sys_geoa','sys_keyword.geo_code_country','=','sys_geoa.code')
                ->leftJoin('sys_geo as sys_geob','sys_keyword.geo_code_province','=','sys_geob.code')
                ->leftJoin('sys_geo as sys_geoc','sys_keyword.geo_code_city','=','sys_geoc.code')
                ->where('sys_keyword.code',$code)
                ->select(['sys_keyword.name','sys_keyword.name_en','sys_keyword.code','oneword','sys_geoa.name as country_name','sys_geob.name as province_name','sys_geoc.name as city_name','birth','logo_path','head_path'])
                ->first();
        $keywords['name'] = $keyinfo->name;
        $keywords['name_en'] = $keyinfo->name_en;
        $keywords['code'] = $keyinfo->code;
        $keywords['oneword'] = $keyinfo->oneword;
        $keywords['city_info'] = $keyinfo->country_name.$keyinfo->province_name.$keyinfo->city_name;
        $keywords['birth_info'] = $this->handletime($keyinfo->birth);
        $keywords['logo_path'] = $keyinfo->logo_path;
        $keywords['head_path'] = $keyinfo->head_path;
        $product = DB::table('xc_core_value')->where(['keyword_code'=>$code, 'type'=>'p'])->select(['id as core_id','name'])->get();
        $tech = DB::table('xc_core_value')->where(['keyword_code'=>$code, 'type'=>'t'])->select(['id as core_id','name'])->get();
        $project = DB::table('xc_core_value')->where(['keyword_code'=>$code, 'type'=>'s'])->select(['id as core_id','name'])->get();
        $people = DB::table('xc_management')
                    ->leftJoin('sys_keyword','xc_management.executive','=','sys_keyword.code')
                    ->leftJoin('sys_type','xc_management.type_code_role','=','sys_type.code')
                    ->select(['xc_management.id','executive as keyword_code','sys_keyword.name','sys_keyword.name_en','sys_type.name as role'])
                    ->where('keyword_code',$code)
                    ->get();
        $data['keyword'] = $keywords;
        if(count($product) > 0) $data['product'] = $product;
        if(count($tech) > 0) $data['tech'] = $tech;
        if(count($project) > 0) $data['project'] = $project;
        if(count($people) > 0) $data['people'] = $people;
        return $data;
    }

    public function handletime($times)
    {
        $year  = substr($times, 0, 4);
        $month = substr($times, 5, 2);
        $day   = substr($times, 8, 2);
        if($month == '00') {
            return $year.'年';
        } else if($month != '00' && $day == '00') {
            return $year.'年'.$month.'月';
        } else {
            return $year.'年'.$month.'月'.$day.'日';
        }
    }


    public function getindustryGrade($industrycode)
    {
        if(empty($industrycode)) return false;
        $inddata = DB::table('sys_industry')->select(['parent'])->where(['code'=>$industrycode])->first();
        if($inddata->parent) {
            $data[] = $industrycode;
            return $data;
        } else {
            $inddatatwo = DB::table('sys_industry')->select(['code'])->where(['parent'=>$industrycode])->get();
            $data[] = $industrycode;
            foreach($inddatatwo as $key => $va) {
                $data[] = $va->code;
            }
            return $data;
        }
    }

}


