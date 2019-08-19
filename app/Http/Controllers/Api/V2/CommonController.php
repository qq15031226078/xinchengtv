<?php
namespace App\Http\Controllers\Api\V2;

use App\Models\Gallery;
use App\Models\Picture;
use App\Models\Corevalue;
use App\Models\Keywords;
use App\Models\KeyWordBlock;
use App\Models\KeyWordBlockInfo;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V2;
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
     * 关键词职务(彦平)
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
     * 通过 code 查出行业类型信息(彦平)
     */
    public function industryFullname($code){
        $industry = [];
        $industryinfo = DB::table('sys_industry')->select(['name','parent'])->where(['code'=>$code,'deleted_at'=>NULL])->first();
        if(empty(collect($industryinfo)))    return NULL;
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
        if(empty($keywordinfo))  return NULL;
        $keywordinfo->birth_info = $this->keywordFound($code);
        return collect($keywordinfo)->toArray();
    }
    /**
     * 关键词创立(PING)
     */
    public function keywordFound($code){
        $keywordinfo = DB::table('sys_keyword')->select(['geo_code_country','geo_code_province','geo_code_city','birth','keyword_category_code'])->where(['code'=>$code,'deleted_at'=>NULL])->first();
        if(empty($keywordinfo)) return NULL;
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
        $data = $this->regionFullname($array['geo_code_country'],$array['geo_code_province'],$array['geo_code_city']);
        $birth = explode('-',$array['birth']);
        $birth[0] = $birth[0] == '0000' ? '' : $birth[0];
        $birth[1] = $birth[1] == '00' ? '' : $birth[1];
        $birth[2] = $birth[2] == '00' ? '' : $birth[2];
        if(!empty($data)){
            $str1 = $data;
            // if($data['city'] != ''){
            //     $str1 = $data['guo'].$data['sheng'].$data['city'];
            // }else{
            //     $str1 = ($data['guo']?:'') . ($data['sheng']?:'');
            // }
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
            $str = $str1.'，'.$flag.$str2;
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
     * 查询出关键词的国家省市区(彦平)
     */
    public function regionFullname($geo_code_country='',$geo_code_province='',$geo_code_city=''){
        $guo = DB::table('sys_geo')->select(['name as guo'])->where(['code'=>$geo_code_country,'deleted_at'=>NULL])->first();
        $sheng = DB::table('sys_geo')->select(['name as sheng'])->where(['code'=>$geo_code_province,'deleted_at'=>NULL])->first();
        $city = DB::table('sys_geo')->select(['name as city'])->where(['code'=>$geo_code_city,'deleted_at'=>NULL])->first();
        $data = (!empty($guo) ? $guo->guo.' ' : '') . (!empty($sheng) ? $sheng->sheng.' ' : '') . (!empty($city) ? $city->city.' ' : '');
        // if(!empty($geo_code_province)){
        //     $data = [];
        //     $region1 = DB::table('sys_geo')->select(['name','parent'])->where(['code'=>$geo_code_province,'deleted_at'=>NULL])->first();
        //     if(!empty($region1->parent)){
        //         $data['city']  = "";
        //         $data['sheng'] = $region1->name;
        //         $data['guo'] = DB::table('sys_geo')->select(['name as guo'])->where(['code'=>$region1->parent,'deleted_at'=>NULL])->first()->guo;
        //     }else{
        //         $data = NULL;
        //     }
        // }elseif(!empty($geo_code_city)){
        //     $data = [];
        //     $region1 = DB::table('sys_geo')->select(['name','parent'])->where(['code'=>$geo_code_city,'deleted_at'=>NULL])->first();
        //     $data['city'] = is_null($region1)?'':$region1->name;
        //     if(!empty($region1->parent)){
        //         $region2 = DB::table('sys_geo')->select(['name as sheng','parent'])->where(['code'=>$region1->parent,'deleted_at'=>NULL])->first();
        //         if(!empty($region2->parent)){
        //             $data['sheng'] = $region2->sheng;
        //             $data['guo'] = DB::table('sys_geo')->select(['name as guo'])->where(['code'=>$region2->parent,'deleted_at'=>NULL])->first()->guo;
        //         }else{
        //             $data = NULL;
        //         }
        //     }
        // }else{
        //     $guo = DB::table('sys_geo')->select(['name as guo'])->where(['code'=>$geo_code_country,'deleted_at'=>NULL])->first()->guo;
        //     if(empty($guo)){
        //         $data = NULL;
        //     }else{
        //         $data['guo']   = $guo;
        //         $data['sheng'] = '';
        //         $data['city']  = '';
        //     }
        // }
        return $data;
    }

    //图库图片信息 ———— 朱朔男
    public function galleryInfo($gallery_id){
		$this->getObject();
		$gallery = $this->gallery->getGalleryOne(['id'=>$gallery_id],['id','title','keyword_code','industry_code']);
        $pictures = $this->picture->getAllPicture(['gallery_id'=>$gallery->id],['desc','thumb_path','original_path','is_cover']);
        foreach($pictures as $key => $va) {
            $pictures[$key]['thumb_path'] = $va['original_path']."!draft200";
        }
        // $gallery->pictures = $this->picture->getAllPicture(['gallery_id'=>$gallery->id],['desc','thumb_path','original_path','is_cover']);
        $gallery->pictures = $pictures;
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
        foreach ($data as $k => $v) {
            $content = $this->keywordblockinfo->getKeyWordBlockContents(['block_id'=>$v->id],'content');
            if(count($content)) {
                $v->contents = $content;
            } else {
                unset($data[$k]);
            }
        }
        return $data;
    }

    // 将新闻概要转换成字符串-----------王磊
    public function transformation($array) {
        if(empty($array)) return null;
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
        $arr = [ 60, 100 ];
        $res = mb_substr( $string, 0, $arr[rand(0,1)], 'utf-8' );
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
        $keywordData = DB::table('db_writing.draft_keyword')->leftJoin('sys_keyword','draft_keyword.keyword_code','=','sys_keyword.code')->where('draft_id',$draftid)->orderBy('draft_keyword.order','asc')->select(['keyword_code','name as keyword_name','logo_path'])->first();
        if($keywordData) {
            $data['keyword_code'] = $keywordData->keyword_code;
            $data['keyword_name'] = $keywordData->keyword_name;
            $data['logo_path']    = $keywordData->logo_path;
        }
        $industrydata = DB::table('db_writing.draft_industry')->where('draft_id',$draftid)->orderBy('order','asc')->select(['industry_code'])->first();
        if($industrydata != NULL){
            $industrydata = $industrydata->industry_code;
        }
        if($industrydata) {
            $data['industry_code'] = $industrydata;
            $data['industry_fullname'] = $this->industryFullname($industrydata)['industry_fullname'];
        }
        $draftdata = DB::table('db_writing.draft')->where('id',$draftid)->select(['id as draft_id','title','subtitle'])->first();
        $draftimg = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'picture'])->leftJoin('xc_picture','draft_body.element_id','=','xc_picture.id')->select(['thumb_path'])->limit(2)->get();
        if($draftdata) {
            $data['draft_id'] = $draftdata->draft_id;
            $data['title'] = $draftdata->title;
            $data['subtitle'] = $draftdata->subtitle;
            if(count($draftimg) > 0) {
                foreach($draftimg as $key => $va) {
                    $data['imgs'][] = $va->thumb_path.'!app540'; 
                }
            }
        }

        $draftintro = DB::table('db_writing.draft_body')->where(['draft_id'=>$draftid, 'element_type'=>'text'])->select(['element_content'])->limit(2)->get();
        $draftintros = '';
        if(count($draftintro) > 0) {
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
        //    $data['intro'] = $this->transformation($draftintros);
        // } else {
        //     $data['intro'] = null;
        // }
        return $data;
    }

    // 关键词code (第一个参数关键词code, 第二个参数 显示图库个数)
    public function keywordGallerys($code,$limit=2)
    {
        if(empty($code)) return null;
        $data = DB::table('xc_gallery')->where(['keyword_code'=>$code, 'deleted_at'=>null])->limit($limit)->select(['id','title'])->get();
        foreach($data as $key => $va) {
            $picture = DB::table('xc_picture')->where(['gallery_id'=>$va->id, 'deleted_at'=>null])->get(['desc','thumb_path','original_path']);
            foreach($picture as $k => $v) {
                $picture[$k]->thumb_path = $v->original_path.'!draft200';
            }
            $data[$key]->picture = $picture;
        }
        return $data;
    }

}


