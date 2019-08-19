<?php
namespace App\Http\Controllers\Api\V2;

use App\Models\Corevalue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeywordController extends CommonController
{
	protected $corevalue;

	function __construct(Corevalue $corevalue){
  	  $this->corevalue = $corevalue;
	}

	/**
     * @api {get} /v2/keyword/gallery/{keyword_code} 关键词：图库
     * @apiDescription 关键词：图库 ———— 朱朔男
     * @apiGroup Keyword
     * @apiPermission 需要验证
     * @apiParam {int}	keyword_code	关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
	 * 	{
	 *	  "statusCode": 200,
	 *	  "statusMessage": "获取数据成功",
	 *	  "responseData": {
	 *	    "keywords": {						// 关键词相关信息
	 * 		  "code": "4KZYFMeqNN8PfwcU",		// 关键词code
	 *	      "name": "Makeblock",				// 中文名
	 *	      "name_en": "Makeblock",			// 英文名
	 * 		  "keyword_category_code": "KC004"	// 关键词类型code
	 *	      "oneword": "编程机器人设计研发商",	// 一句话
	 *	      "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial",	// 缩略图
	 *	      "head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial",	// 头图
	 *	      "birth_info": "成立于：1824年"		// 成立时间
	 *	    },
	 *	    "gallerys": [						// 图库相关信息
	 *	      {
	 *	        "gallery_id": 24,				// 图库id
	 *	        "title": "Makeblock产品",		// 标题
	 *	        "picture": [					// 图库下面的图片
	 *	          {
	 *	            "desc": "",				// 图片描述
	 *	        	"thumb_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496651891.jpg!news.logo.500.270",	// 缩略图
	 *	        	"original_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496651891.jpg!news.logo.500.270",	// 原图
	 *	        	"is_cover": 1			// 是否为封面（0-否，1-是）
	 *	          }
	 *	        ],
	 * 			"num": 10					// 图片数量
	 *	      }
	 *	    ]
	 *	  }
	 *	}
     */
    public function KeywordGallery($keyword_code){
    	$gallerys = [];
    	if (empty($keyword_code)) return $this->responseFromat(530,[],'缺少keyword_code参数');
    	if (!is_string($keyword_code)) return $this->responseFromat(512);
        $keyword = $this->keywordInfo($keyword_code);
        $data['keywords'] = $keyword;
        $gallery_ids = DB::table('xc_gallery')->where(['keyword_code'=>$keyword_code])->orderBy('order','asc')->orderBy('created_at','asc')->select('id')->get();
        foreach ($gallery_ids as $v) {
    		$gallerys[] = $this->galleryInfo($v->id);
    	}
    	$data['gallerys'] = $gallerys;
        return $this->responseFromat(200, $data);
    }

    /**
     * @api {get} /v2/keyword/achievement/{keyword_code} 关键词：进展
     * @apiDescription 关键词：进展 ———— 朱朔男
     * @apiGroup Keyword
     * @apiPermission 需要验证
     * @apiParam {int}	keyword_code	关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *  {
	 *	  "statusCode": 4000,
	 *	  "statusMessage": "获取数据成功",
	 *	  "responseData": {
	 * 		"keywords": {
	 *	        "code": "4KZYFMeqNN8PfwcU",			// 关键词code
	 *	      	"name": "Makeblock",				// 中文名
	 *	      	"name_en": "Makeblock",				// 英文名
	 * 		  	"keyword_category_code": "KC004"	// 关键词类型code
	 *	      	"oneword": "编程机器人设计研发商",	// 一句话
	 *	      	"logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial",	// 缩略图
	 *	      	"head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial",	// 头图
	 *	      	"birth_info": "成立于：1824年"		// 成立时间
	 *	    },
	 *	    "project": [							// 项目
	 *	      {
	 *	        "id": 1040,							// 项目id
	 *	        "name": "卫星平台型谱方案",			// 中文名						
	 *	        "name_en": "Family of Satellite Buses",				// 英文名
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1506313482.jpg!ptpe.img",		// 缩略图
	 *	        "content": [						// 信息条
	 *	          "洛克希德·马丁推出卫星平台型谱方案，是为了让不同型号的卫星能够共用某些通用部件，以便更快、更低成本地完成不同 *用户对于卫星大小、任务及其轨道选项等个性化需求的订单。",
	 *	          "卫星平台型谱方案一共纳入4种卫星平台，它们分别是LM 50系列平台、LM 400系列平台、LM 1000系列平台和LM 2100系列平台。",
	 *	          "LM 50系列平台：该平台生产10-100公斤的纳米卫星，主要供美国空军或商业用户开展空间实验。",
	 *	          "LM 400系列平台：该平台生产140-800公斤的小卫星，且在推进系统上有所改进，以用来执行低地轨道、近地轨道以及星际任务，其成本可比以前降低20%-30%，交货速度则能提高3倍。",
	 *	          "LM 1000系列平台：该平台生产275-2200公斤的中、大卫星，可以携带较多载荷来执行多种轨道和星际任务。",
	 *	          "LM 2100系列平台：该平台生产2300公斤左右的大卫星，是在洛克希德·马丁此前的通信卫星系列A 2100的基础上的改进型号，主要以提高功率和灵活性为原则，对包括太阳能电池阵和霍尔推进器在内的26项技术进行了改进。"
	 *	        ]
	 *	      },
	 *	      {
	 *	        "name": "小型核聚变反应堆",
	 *	        "id": 969,
	 *	        "name_en": "",
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500271792.jpg!ptpe.img",
	 *	        "content": []
	 *	      },
	 *	      {
	 *	        "name": "USC-洛克希德·马丁公司量子计算中心",
	 *	        "id": 970,
	 *	        "name_en": "",
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500272107.jpg!ptpe.img",
	 *	        "content": []
	 *	      }
	 *	    ],
	 *	    "product": [							// 产品
	 *	      {
	 *	        "name": "超音速喷气式客机",
	 *	        "id": 509,
	 *	        "name_en": "X-Plane",
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///prod/prod1500271562.jpg!ptpe.img",
	 *	        "content": []
	 *	      },
	 *	      {
	 *	        "name": "商用超音速喷气式客机",
	 *	        "id": 510,
	 *	        "name_en": "N+2",
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///prod/prod1500271490.jpg!ptpe.img",
	 *	        "content": []
	 *	      }
	 *	    ],
	 *	    "tech": [							// 科技
	 *	      {
	 *	        "name": "3D打印军用卫星部件",
	 *	        "id": 820,
	 *	        "name_en": "",
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500271690.jpg!ptpe.img",
	 *	        "content": []
	 *	      }
	 *	    ]
	 *	  }
	 *	}
     */
    public function KeywordAchievement($keyword_code){
    	if (empty($keyword_code)) return $this->responseFromat(530,[],'缺少keyword_code参数');
    	if (!is_string($keyword_code)) return $this->responseFromat(512);
    	$keyword = $this->keywordInfo($keyword_code);
        $data['keywords'] = $keyword;
        $project = $this->CoreValue($keyword_code,'s');
        $product = $this->CoreValue($keyword_code,'p');
        $tech = $this->CoreValue($keyword_code,'t');
        if(count($project))  {
            $data['project'] = $project;
        } else {
            $data['project'] = null;
        }
        if(count($product)) {
            $data['product'] = $product;
        } else {
            $data['product'] = null;
        }
        if(count($tech)) {
            $data['tech'] = $tech;
        } else {
            $data['tech'] = null;
        }
    	return $this->responseFromat(200, $data);
    }

    // 获取产科项信息
    public function CoreValue ($keyword_code,$type){
    	$data = DB::table('xc_core_value')->where(['keyword_code'=>$keyword_code,'type'=>$type,'deleted_at'=>null])->orderBy('order','asc')->orderBy('created_at','desc')->select('id','name','name_en','thumb_path')->get();
    	foreach ($data as $k => $v) {
            $content = DB::table('data_core_record')->where(['core_id'=>$v->id, 'deleted_at'=>null])->orderBy('order','asc')->orderBy('created_at','asc')->pluck('content');
            $v->thumb_path = $v->thumb_path . "!900x480";
    		if(count($content)) {
                $v->content = $content;
            } else {
                unset($data[$k]);
            }
    	}
    	return $data;
    }

    /**
     * @api {get} /v2/keyword/core/{core_id} 产科项详情
     * @apiDescription 产科项详情 ———— 朱朔男
     * @apiGroup Content
     * @apiPermission 需要验证
     * @apiParam {int}	core_id	产科项id
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *  {
	 *	  "statusCode": 4000,
	 *	  "statusMessage": "获取数据成功",
	 *	  "responseData": {
	 *	    "keywords": {
	 *	        "code": "4KZYFMeqNN8PfwcU",			// 关键词code
	 *	      	"name": "Makeblock",				// 中文名
	 *	      	"name_en": "Makeblock",				// 英文名
	 * 		  	"keyword_category_code": "KC004"	// 关键词类型code
	 *	      	"oneword": "编程机器人设计研发商",	// 一句话
	 *	      	"logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial",	// 缩略图
	 *	      	"head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial",	// 头图
	 *	      	"birth_info": "成立于：1824年"		// 成立时间
	 *	    },
	 *	    "cores": {								// 产科项详情
	 *	        "id": 746,							// 产科项id
	 *	        "name": "超音速喷气式客机",			// 中文名
	 *	        "name_en": "X-Plane",				// 英文名
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///prod/prod1500271562.jpg!ptpe.img",		// 缩略图
	 *	        "contents": [						// 内容
	 *	          "洛克希德·马丁推出卫星平台型谱方案，是为了让不同型号的卫星能够共用某些通用部件，以便更快、更低成本地完成不同 *用户对于卫星大小、任务及其轨道选项等个性化需求的订单。",
	 *	          "卫星平台型谱方案一共纳入4种卫星平台，它们分别是LM 50系列平台、LM 400系列平台、LM 1000系列平台和LM 2100系列平台。",
	 *	          "LM 50系列平台：该平台生产10-100公斤的纳米卫星，主要供美国空军或商业用户开展空间实验。",
	 *	          "LM 400系列平台：该平台生产140-800公斤的小卫星，且在推进系统上有所改进，以用来执行低地轨道、近地轨道以及星际任务，其成本可比以前降低20%-30%，交货速度则能提高3倍。",
	 *	          "LM 1000系列平台：该平台生产275-2200公斤的中、大卫星，可以携带较多载荷来执行多种轨道和星际任务。",
	 *	          "LM 2100系列平台：该平台生产2300公斤左右的大卫星，是在洛克希德·马丁此前的通信卫星系列A 2100的基础上的改进型号，主要以提高功率和灵活性为原则，对包括太阳能电池阵和霍尔推进器在内的26项技术进行了改进。"
	 *	        ]
	 *	    }
	 *	  }
	 *	}
     */
    public function KeywordCore($core_id){
    	if (empty($core_id)) return $this->responseFromat(530,[],'缺少core_id参数');
    	if (!is_numeric($core_id)) return $this->responseFromat(510);
    	$core = $this->corevalue->getCorevalueOne(['id'=>$core_id],['id','name','name_en','thumb_path','keyword_code']);
    	$core['contents'] = DB::table('data_core_record')->where(['core_id'=>$core['id'], 'deleted_at'=>null])->orderBy('order','asc')->orderBy('created_at','asc')->pluck('content');
        $keyword = $this->keywordInfo($core['keyword_code']);
        unset($core['keyword_code']);
        $data['keywords'] = $keyword;
        $data['cores'] = $core;
    	return $this->responseFromat(200, $data);
    }

    /**
     * @api {get} /v2/keyword/block/{keyword_code} 关键词：信息块
     * @apiDescription 关键词信息块 ———— 朱朔男
     * @apiGroup Keyword
     * @apiPermission 需要验证
     * @apiParam {int}	keyword_code	关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
	 *	{
	 *	    "statusCode": 200,
	 *	    "statusMessage": "获取数据成功",
	 *	    "responseData": {
	 *	        "keywords": {
	 *	            "code": "4KZYFMeqNN8PfwcU",			// 关键词code
	 *	      		"name": "Makeblock",				// 中文名
	 *	      		"name_en": "Makeblock",				// 英文名
	 * 		  		"keyword_category_code": "KC004"	// 关键词类型code
	 *	      		"oneword": "编程机器人设计研发商",	// 一句话
	 *	      		"logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1496651778.jpg!initial",	// 缩略图
	 *	      		"head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1496651781.jpg!initial",	// 头图
	 *	      		"birth_info": "成立于：1824年"		// 成立时间
	 *	        },
	 *	        "blocks": [								// 关键词信息块
	 *	            {
	 *	                "id": 299,						// 信息块id
	 *	                "title": "业务方向",				// 信息块标题
	 *	                "contents": [					// 信息块内容
	 *			          "曼彻斯特大学分为生物、医学与健康院系、科学与工程院系和人文院系三个院系，每个院系由多个学院组成。",
	 *			          "学校有三个学院，分别是工程学院、自然科学学院和社会科学和人文艺术学院，学生们可以学习19个专业和18个副修专业。"
	 *			        ]
	 *	            }
	 *	        ]
	 *	    }
	 *	}
     */
    public function KeywordBlock($keyword_code){
    	if (empty($keyword_code)) return $this->responseFromat(530,[],'缺少keyword_code参数');
    	if (!is_string($keyword_code)) return $this->responseFromat(512);
        $keyword = $this->keywordInfo($keyword_code);
        unset($keyword['desc']);
        $data['keywords'] = $keyword;
        $data['blocks'] = $this->blockInfo($keyword_code);
        return $this->responseFromat(200, $data);
    }

    /**
     * @api {get} /v2/keyword/news 关键词：新闻
     * @apiDescription 关键词：新闻 (彦平)
     * @apiGroup Keyword
     * @apiPermission 需要验证
     * @apiParam {int}	keyword_code	关键词code
     * @apiParam {int}	page	        页码(非必传，不传默认为1)
     * @apiParam {int}	limit       	每页显示数量(非必传,默认：5)
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "keywords": {
     *                  "code": "gp51YWgemGows7Dw",             // 关键词code
     *                  "name_cn": "Perceptive Pixel",          // 关键词中文名称
     *                  "name_en": "Perceptive Pixel",          // 关键词英文名称
     *                  "category_code": "KC002",               // 关键词类型code
     *                  "oneword": "多点触控面板设计研发商",    // 关键词一句话
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1495098128.jpg!initial",     //关键词logo
     *                  "head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1496887552.jpg!initial",     //关键词头图
     *                  "desc": "Perceptive Pixel于2006年成立于纽约，是一家多点触控面板设计研发商，创始人是Jefferson Han。2012年，该公司被微软收购。",     //关键词描述
     *                  "birth_info": "创立于：2006年"           //关键词生日、创立信息
     *              },
     *              "news": {
     *                  "current_page": 1,
     *                  "last_page": 1,
     *                  "info": [
     *                      {
     *                          "keyword_code": "QujrFl7lIYOxssWM",
     *                          "keyword_name": "FOVE",
     *                          "industry_code": "IND0022",
     *                          "industry_fullname": "虚拟现实 / 头戴式显示器",
     *                          "draft_id": 4,
     *                          "title": "能追踪眼球的VR头盔面世",
     *                          "subtitle": "局部变焦或将引变革",
     *                          "imgs": [                                       //新闻图片
     *                              "http://uploads2.b0.upaiyun.com///news/news1496654357.jpg",
     *                              "http://uploads2.b0.upaiyun.com///news/news1496654364.jpg",
     *                          ],
     *                          "intro": "根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学"      //新闻简介                             //新闻简介
     *                      }
     *                  ]
     *              }
     *          }
     *      }
     */
    public function KeywordNews(Request $request)
    {
        $this->validate($request, [
            'keyword_code' => 'required',
        ]);
        $code = $request->get('keyword_code');
        do{
            $res = [];
            $news_id_arr = DB::table('xc_content')->select(['news_id'])->groupBy('news_id')->where(['keyword_code'=>$code,'deleted_at'=>NULL])->paginate($request->get('limit', 5));
            // if(count($news_id_arr)<1){
            //     $code = 501;
            //     $response = NULL;
            //     $message = '没有更多数据';
            //     break;
            // }
            $res['keywords'] =  $this->keywordInfo($code);
            $res['news']['current_page'] = $news_id_arr->currentPage();
            $res['news']['last_page']    = $news_id_arr->lastPage();
            $res['news']['info'] = [];
            foreach($news_id_arr as $val){
                $newsinfo = $this->draftInfo($val->news_id);
                if($newsinfo) {
                    $res['news']['info'][]  = $newsinfo;
                }
            }
            $code = 200;
            $response = $res;
            $message = NULL;
        }while(0);
        return $this->responseFromat($code,$response,$message);
    }

    // 搜索关键词或新闻
    /**
     * @api {get} /v2/search 搜索关键词或新闻
     * @apiDescription 搜索关键词或新闻 (彦平)
     * @apiGroup Keyword
     * @apiPermission 需要验证
     * @apiParam {String}  search    搜索词汇
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
{
    "statusCode": 200,
    "statusMessage": null,
    "responseData": {
        "keywords": null,
        "news": {
            "current_page": 1,
            "last_page": 1,
            "info": [
                {
                    "keyword_code": "KEY000117",
                    "keyword_name": "Snapchat",
                    "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1492071979.jpg",
                    "industry_code": "IND0064",
                    "industry_fullname": "互联网 / 即时通讯",
                    "draft_id": 11,
                    "title": "阿里巴巴豪掷两亿",
                    "subtitle": "赌阅后即焚应用Snapchat",
                    "imgs": [
                        "http://uploads2.b0.upaiyun.com/news/news1496672197.jpg!app540"
                    ],
                    "intro": "2015年年初，美国00后最喜欢的照片社交分享应用Snapchat完成了新一轮高达4.86亿美元的融资，市场估值为150亿美元。据悉，共有23家投资方参与了本轮投资，其中中国电商巨头阿里巴巴为Snap……"
                }
            ]
        }
    }
}
     */
    public function search(Request $request){
        $search = $request->input('search');
        do {
            if(empty($search)) {
                $code = 422;
                $response = [];
                $message = '参数不能为空';
                break;
            }
            // 先找新闻
            $news_id_by_draft = json_decode(DB::table('draft')->select(['id as news_id'])->where('title','like','%'.$search.'%')->orWhere('subtitle','like','%'.$search.'%')->get(),TRUE);
            $news_id1 = $news_id2 = $keyword_codes = $news_info = [];
            foreach ($news_id_by_draft as $val) $news_id1[] = $val['news_id'];
            // 找出关键词新闻
            $key_code = DB::table('sys_keyword')->select(['code as keyword_code'])->where('name','like','%'.$search.'%')->orWhere('name_en','like','%'.$search.'%')->get();
            foreach ($key_code as $val) $keyword_codes[] = $val->keyword_code;
            $news_id_by_keyword = json_decode(DB::table('draft_keyword')->select(['draft_id as news_id'])->whereIn('keyword_code',$keyword_codes)->get(),TRUE);
            foreach ($news_id_by_keyword as $val) $news_id2[] = $val['news_id'];
            // 合并且去重
            $news_id = array_unique(array_merge($news_id1,$news_id2));
            // 查出新闻
            foreach ($news_id as $val) $news_info[] = $this->draftInfo($val);
            //数据格式模仿关键词新闻 
            if(count($news_info)>0){
                array_multisort(array_column($news_info,'addtime'),SORT_DESC,$news_info);
                $data['keywords'] = NULL;
                $data['news']['current_page'] = 1;
                $data['news']['last_page'] = 1;
                $data['news']['info'] = $news_info;
            }else{
                $data = [];
            }
            

            $code = 200;
            $response = $data;
            $message = NULL;
        } while (0);
        return $this->responseFromat($code,$response,$message);
    }


}