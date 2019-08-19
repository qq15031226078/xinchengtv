<?php
namespace App\Http\Controllers\Api\V2\Pc;

use DB;
use Illuminate\Http\Request;

class HomeController extends CommonController
{
    public function __construct()
    {
        $this->res = [
            'code'     => '',
            'response' => [],
            'message'  => NULL
        ];
    }

    public function index(Request $request)
    {
    	$val = $request->all();
    	return $this->draftInfo($val['id']);
    }

    // 轮播图
    /**
     * @api {get} /pc/v2/slide    首页轮播图
     * @apiDescription 首页轮播图 --- 彦平
     * @apiGroup PC
     * @apiPermission 需要
     * @apiParam {string} industry_code    行业类型code(非必传----不传：首页;传:非首页)
     * @apiVersion 0.0.2
     * @apiSuccessExample {json} Success-Response:
    {
        "statusCode": 200,
        "statusMessage": "获取数据成功",
        "responseData": {
            "slide": [
                {
                    "id": 2,                                    //轮播图id
                    "type": 0,                                  //轮播图类型 ：0-新闻；1-新城商业；2-像素科技；3-新城tv
                    "title": "谷歌收购云应用管理平台 Bitium",   //轮播图标题
                    "subtitle": "继续追赶微软亚马逊",           //轮播图副标题
                    "intro": "谷歌宣布收购云应用管理平台 Bitium\n该公司解决了企业批量管理云应用的痛点\n此次收购将使谷歌云获得大量用户",   //轮播图描述
                    "photo": "http://xincheng-img.b0.upaiyun.com/xincheng-img-pDosZ3Aonk.jpeg",     //轮播图图片
                    "url": "817"                                // 新闻、新城商业、像素科技、新城tv 对应的id
                },
                {
                    "id": 1,
                    "type": 0,
                    "title": "现在它们要联合在核废料处理厂大显身手",
                    "subtitle": "无人机和机器人的应用越来越广泛",
                    "intro": "无人驾驶卡车公司Embark获得Data Collective领投的1500万美元A轮融资\nEmbark的无人驾驶卡车基于彼得比尔特牌卡车改装，具有激光雷达、毫米波雷达以及摄像头",
                    "photo": "http://xincheng-img.b0.upaiyun.com/xincheng-img-J7X28hTL4a.jpeg",
                    "url": "816"
                }
            ]
        }
    }
     */
    public function slide(Request $request)
    {
        $res = [];
        $industry_code = $request->input('industry_code',NULL);
        do{
            if($industry_code == NULL){
                $res['slide'] = DB::table('xc_recommend')->select('id','type','title','subtitle','intro','photo','url')->limit(4)->orderBy('id','desc')->where(['position'=>1,'deleted_at'=>NULL])->get();
            }else{
                $res['slide'] = DB::table('xc_recommend')->select('id','type','title','subtitle','intro','photo','url')->limit(4)->orderBy('id','desc')->where(['deleted_at'=>NULL])->where('industry_code','like','%'.$industry_code.'%')->get();
            }
            
            if(count($res['slide']->toArray())<1){
                $code = 501;
                $response = [];
                $message = '没有更多数据';
                break;
            }
            $code = 200;
            $response = $res;
            $message = NULL;
        }while(0);
        return $this->responseFromat($code,$response,$message);
    }

    // 首页节目
    /**
     * @api {get} /pc/v2/program    首页节目
     * @apiDescription 首页节目 --- 彦平
     * @apiGroup PC
     * @apiPermission 需要
     * @apiVersion 0.0.2
     * @apiSuccessExample {json} Success-Response:
    {
        "statusCode": 200,
        "statusMessage": "获取数据成功",
        "responseData": {
            "business": [                       //新城商业  2个
                {
                    "id": 2,                    //节目id
                    "program_type": 1,          //节目类型：1-新城商业(默认),2-像素科技,3-新城TV
                    "title": "他要做中国版的SpaceX",   //节目标题
                    "subtitle": "",                    //节目副标题
                    "type_code_website": "ST00122",   //出处code
                    "type_name": "七牛",   //出处名称
                    "issue": "第118期",                 //期数
                    "thumb_path": "http://uploads2.b0.upaiyun.com///vb/vb1497491590.jpg",   //图片
                    "link": "XMjc5ODg1NTc5Ng"           //链接
                }
            ],
            "pixel": [                          //像素科技  4个
                {
                    "id": 188,                  //节目id
                    "program_type": 1,          //节目类型：0-新城商业(默认),1-像素科技,2-新城TV
                    "title": "共享经济进军太空卫星",      //节目标题
                    "subtitle": "",             //节目副标题
                    "type_code_website": "ST00122",   //出处code
                    "type_name": "七牛",   //出处名称
                   "issue": "第118期",          //期数
                    "thumb_path": "http://uploads2.b0.upaiyun.com///vb/vb1506644531.jpg",   //图片
                    "link": "XMzA1NDQzMDQyNA"   //链接
                },
                {
                    "id": 166,
                    "program_type": 1,
                    "title": "电影中的生化蜻蜓已出现",
                    "subtitle": "",
                    "type_code_website": "ST00122",   //出处code
                    "type_name": "七牛",   //出处名称
                    "issue": "第118期",
                    "thumb_path": "http://uploads2.b0.upaiyun.com///vb/vb1504824380.jpg",
                    "link": "XMzAxMjIwNDE5Mg"
                }
            ]
        }
    }
     */
    public function program()
    {
        $res = [];
        do{
            // $res['business'] = DB::table('xc_program')->select('id','program_type','title','subtitle','type_code_website','issue','thumb_path','link')->where(['program_type'=>1,'deleted_at'=>NULL])->limit(4)->orderBy('id','desc')->get();

            $res['business'] = DB::table('xc_program as p')->join('sys_type as t','p.type_code_website','=','t.code','left')->select('p.id','p.program_type','p.title','p.subtitle','p.type_code_website','t.name as type_name','p.issue','p.thumb_path','p.link')->where(['p.program_type'=>1,'p.deleted_at'=>NULL])->limit(2)->orderBy('p.id','desc')->get();

            $res['pixel']    = DB::table('xc_program as p')->join('sys_type as t','p.type_code_website','=','t.code','left')->select('p.id','p.program_type','p.title','p.subtitle','p.type_code_website','t.name as type_name','p.issue','p.thumb_path','p.link')->where(['p.program_type'=>2,'p.deleted_at'=>NULL])->limit(2)->orderBy('p.id','desc')->get();
            if(count($res['business']->toArray())<1 && count($res['pixel']->toArray())<1){
                $code = 501;$response = [];$message = '没有更多数据';break;
            }
            if(count($res['business']->toArray())<1 ) $res['business'] = NULL;
            if(count($res['pixel']->toArray())<1)  $res['pixel'] = NULL;
            if($res['business'] != NULL){
                foreach($res['business'] as $val) $val->issue = '第'.$val->issue.'期';
            }
            if($res['pixel'] != NULL){
                foreach($res['pixel'] as $val) $val->issue = '第'.$val->issue.'期';
            }
            $code = 200;
            $response = $res;
            $message = NULL;
        }while(0);
        return $this->responseFromat($code,$response,$message);

    }
}