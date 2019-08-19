<?php
namespace App\Http\Controllers\Api\V2;

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
    /**
     * 返回数据方法: responseFromat(错误码, 返回数据, 自定义消息)
     * 跨库查询: DB::connection('writing')->table('draft')->get();
     *
     */

    /**
     * @api {get} /v2/home    首页
     * @apiDescription 首页 --- 彦平
     * @apiGroup Main
     * @apiPermission 需要
     * @apiParam {int}	page	    页码(非必传，不传默认为1)
     * @apiParam {int}	limit       每页显示数量(非必传,默认：5)
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
    {
        "statusCode": 200,
        "statusMessage": "获取数据成功",
        "responseData": {
            "current_page": 1,
            "last_page": 1,
            "data": [
                {
                    "keyword_code": "UBDp4pOVRKf0q2wW",             //关键词code
                    "keyword_name": "NextVR",                       //关键词名称
                    "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1491558672.jpg!initial",// 缩略图
                    "industry_code": "IND0020",                     //行业类型code
                    "industry_fullname": "虚拟现实 / 虚拟现实直播", //一二级行业类型
                    "draft_id": 1,                                  //新闻id
                    "title": "NextVR领衔",                          //新闻标题
                    "subtitle": "虚拟现实VR直播来袭",               //新闻副标题
                    "imgs": [                                       //新闻图片
                        "http://uploads2.b0.upaiyun.com///news/news1496654357.jpg",
                        "http://uploads2.b0.upaiyun.com///news/news1496654364.jpg",
                        "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg"
                    ],
                    "intro": "根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学、李嘉诚基金会、市环保局负责配合做好此项工作，由汕头市环境保护研究所抽调精干专业技术力量协调配合开展科研项目调查采样，协助Nanospun科技有限公司开展科研项目初步研究、资料收集和地点选择等一系列工作；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂"   //新闻简介
                },
                {
                    "keyword_code": "svC6eXb8uKQoWULI",
                    "keyword_name": "UrtheCast",
                    "industry_code": "IND0031",
                    "industry_fullname": "卫星技术 / 应用卫星",
                    "draft_id": 2,
                    "title": "这家公司把摄像头安到了国际空间站",
                    "subtitle": "想要从太空中直播地球",
                    "imgs": [                                       //新闻图片
                        "http://uploads2.b0.upaiyun.com///news/news1496654357.jpg",
                        "http://uploads2.b0.upaiyun.com///news/news1496654364.jpg",
                        "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg"
                    ],
                    "intro": null                                   //新闻简介
                },
                {
                    "keyword_code": "QujrFl7lIYOxssWM",
                    "keyword_name": "FOVE",
                    "industry_code": "IND0022",
                    "industry_fullname": "虚拟现实 / 头戴式显示器",
                    "draft_id": 4,
                    "title": "能追踪眼球的VR头盔面世",
                    "subtitle": "局部变焦或将引变革",
                    "imgs": [                                       //新闻图片
                        "http://uploads2.b0.upaiyun.com///news/news1496654357.jpg",
                        "http://uploads2.b0.upaiyun.com///news/news1496654364.jpg",
                        "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg"
                    ],
                    "intro": null                                   //新闻简介
                }
            ]
        }
    }
     */
    public function index(Request $request)
    {
        do{
            $res = [];
            $news_id_arr = DB::table('xc_content')->
                                select(['news_id'])->
                                groupBy('news_id')->
                                where(function($query){
                                    $query->where('position','1')->orWhere('position','3');
                                })->
                                orderBy('news_id','desc')->
                                paginate($request->get('limit', 5));
            if(count($news_id_arr)<1){
                $code = 501;
                $response = [];
                $message = '没有更多数据';
                break;
            }
            $res['current_page'] = $news_id_arr->currentPage();
            $res['last_page']    = $news_id_arr->lastPage();
            foreach($news_id_arr as $val){
                $res['data'][]  = $this->draftInfo($val->news_id);
            }
            $code = 200;
            $response = $res;
            $message = NULL;
        }while(0);
        return $this->responseFromat($code,$response,$message);
    }

    /**
     * @api {get} /v2/home/channel 频道：首页
     * @apiDescription 频道首页 --- 彦平
     * @apiGroup Channel
     * @apiPermission 需要验证
     * @apiParam {string}	industry_code	行业类型code(必传  eg: IND0021)
     * @apiParam {int}	    page	页码(非必传，不传默认为1)
     * @apiParam {int}	    limit	每页显示数量(非必传，不传默认为5)
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "current_page": 1,
     *              "last_page": 1,
     *              "data": [
     *                  {
     *                      "keyword_code": "QujrFl7lIYOxssWM",         //关键词code
     *                      "keyword_name": "FOVE",                     //关键词名称
     *                      "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1491558672.jpg!initial",// 缩略图
     *                      "industry_code": "IND0022",                 //行业类型code
     *                      "industry_fullname": "虚拟现实 / 头戴式显示器",   //一二级行业类型
     *                      "draft_id": 4,                                //新闻id
     *                      "title": "能追踪眼球的VR头盔面世",            //新闻标题
     *                      "subtitle": "局部变焦或将引变革",            //新闻副标题
     *                      "imgs": [                                   //新闻图片
     *                          "http://uploads2.b0.upaiyun.com///news/news1496654247.jpg",
     *                          "http://uploads2.b0.upaiyun.com///news/news1496654247.jpg"
     *                      ],
     *                      "intro": null                               //新闻简介
     *                  }
     *              ]
     *          }
     *      }
     */
    public function channel(Request $request){
        $this->validate($request, [
            'industry_code' => 'required',
        ]);
        do{
            $code = $request->input('industry_code');  //IND0005, IND0040
            $pos = strpos($code,'IND');
            if($pos === false){
                $code = 530;$response = NULL;$message = '参数错误';break;
            }
            $codes = DB::table('sys_industry')->select(['code'])->where(['parent'=>$code,'deleted_at'=>NULL])->get();
            $where_in = [];
            if(empty($codes->toArray())){
                $where_in[] = $code;
            }else{
                foreach($codes as $val) $where_in[] = $val->code;
            }
            $news_id_arr = DB::table('xc_content')->
                                select(['news_id'])->
                                groupBy('news_id')->
                                where(function($query){
                                    $query->where('position','2')->orWhere('position','3');
                                })->
                                orderBy('news_id','desc')->
                                whereIn('industry_code',$where_in)->
                                paginate($request->get('limit', 5));
            if(count($news_id_arr)<1){
                $code = 501;$response = NULL;$message = '没有更多数据';break;
            }
            $res['current_page'] = $news_id_arr->currentPage();
            $res['last_page']    = $news_id_arr->lastPage();
            foreach($news_id_arr as $val){
                $res['data'][]  = $this->draftInfo($val->news_id);
            }
//            print_r($res);die;
            $code = 200;
            $response = $res;
            $message = '';
        }while(0);
        return $this->responseFromat($code, $response, $message);
    }

}