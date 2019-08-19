<?php
namespace App\Http\Controllers\Api\V2\Pc;

use App\Models\Keywords;

use DB;
use Illuminate\Http\Request;

class NewsController extends CommonController
{

    /**
     * @api {get} /pc/v2/news/list 新闻列表
     * @apiDescription 新闻列表------------------------------王磊
     * @apiGroup PC
     * @apiPermission 需要验证
     * @apiParam {int}      limit          显示条数
     * @apiParam {int}      page           页码
     * @apiParam {int}      industry_code  行业code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": [
     *              {
     *                  "news": { // 新闻
     *                      "keyword_code": "UBDp4pOVRKf0q2wW", // 关键词code
     *                      "draft_id": 1, // 新闻ID
     *                      "title": "NextVR领衔", // 新闻主标题
     *                      "subtitle": "虚拟现实VR直播来袭",  // 新闻副标题
     *                      "imgs": [ // 新闻图片
     *                          "http://uploads2.b0.upaiyun.com///news/news1496654357.jpg",
     *                          "http://uploads2.b0.upaiyun.com///news/news1496654364.jpg",
     *                          "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg"
     *                      ],
     *                      "gallerys": { // 新闻图库
     *                          "id": 1, // 新闻图库ID
     *                          "title": "Solar Probe Plus太阳探测器", // 新闻图库标题
     *                          "picture": [ // 新闻图库图片
     *                              {
     *                                  "thumb_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270", // 新闻图库图片url
     *                                  desc": "" // 新闻图库图片介绍
     *                              },
     *                          ]
     *                      },
     *                      "intro": [ // 新闻概要
     *                          "凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂。",
     *                          "凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂。"
     *                      ]
     *                  },
     *                  "keywords": { // 新闻关键词侧边栏
     *                      "keyword": { // 新闻关键词
     *                          "name": "DeepMind", // 新闻关键词中文名
     *                          "name_en": "DeepMind", // 新闻关键词英文名
     *                          "code": "YO2QEELHzE5YonZC", // 新闻关键词code
     *                          "oneword": "神经网络技术研发企业", // 新闻关键词一句话
     *                          "city_info": "英国伦敦", // 新闻关键词城市
     *                          "birth_info": "2010年09月23日", // 新闻关键词时间
     *                          "logo_path": "http://uploads2.b0.upaiyun.com///keywords1/key1490953288.jpg!initial", // 新闻关键词logo
     *                          "head_path": "http://uploads2.b0.upaiyun.com///keywords2/key1490953288.jpg!initial" // 新闻关键词缩略图
     *                      },
     *                      "product": [ // 新闻关键词产品
     *                          {
     *                              "core_id": 128,
     *                              "name": "阿尔法狗"
     *                          }
     *                      ],
     *                      "tech": [ // 新闻关键词科技
     *                          {
     *                              "core_id": 800,
     *                              "name": "深度强化学习方法"
     *                          },
     *                      ],
     *                      "project": [ // 新闻关键词项目
     *                          {
     *                              "core_id": 986,
     *                              "name": "嵌入式学习库"
     *                          },
     *                      ],
     *                      "people": [ // 新闻关键词人物
     *                          {
     *                              "id": 38,
     *                              "keyword_code": "Po44Dk1las6yJ6TA", //新闻关键词人物code
     *                              "name": "David Silver", //新闻关键词人物中文名
     *                              "name_en": "David Silver", //新闻关键词人物英文名
     *                              "role": "CTO" //新闻关键词人物职务
     *                          },
     *                      ]
     *                  }
     *              }
     *          ]
     *      }
     */ 
    public function lists(Request $request)
    {
    	$val = $request->all();
        $validator = \Validator::make($val, [
            'limit' => 'numeric',
            'page'  => 'numeric',
        ]);
        if ($validator->fails()) return $this->responseFromat(501,$validator);
        $limit = empty($val['limit']) ? 5 : $val['limit'];
        $where = [];
        if(!empty($val['industry_code'])) {
            $incode = $this->getindustryGrade($val['industry_code']);
            $contentnews = DB::table('xc_content')
                               ->whereIn('industry_code',$incode)
                               ->where(function($query){
                                    $query->
                                    where(['position'=>'2', ['keyword_code','!=',''], ['news_id','!=',''], 'deleted_at'=>null])->
                                    orWhere(['position'=>'3', ['keyword_code','!=',''], ['news_id','!=',''], 'deleted_at'=>null]);
                                })
                               ->select(['news_id'])
                               ->orderBy('news_id','desc')
                               ->paginate($limit);
        } else {
            $contentnews = DB::table('xc_content')
                               ->where(function($query){
                                    $query->
                                    where(['position'=>'1', ['keyword_code','!=',''], ['news_id','!=',''], 'deleted_at'=>null])->
                                    orWhere(['position'=>'3', ['keyword_code','!=',''], ['news_id','!=',''], 'deleted_at'=>null]);
                                })
                               ->select(['news_id'])
                               ->orderBy('news_id','desc')
                               ->paginate($limit);
        }
        if($contentnews->toArray()['data']) {
            foreach($contentnews as $key => $va) {
                $newsinfo = $this->draftInfo($va->news_id);
                $data['news'] = $newsinfo;
                $data['keywords'] = $this->keyinfo($newsinfo['keyword_code']);
                $datainfo[] = $data;
            }
            return $this->responseFromat(200,$datainfo);
        } else {
    	    return $this->responseFromat(501,null,'没有数据！');
        }
    }

    /**
     * @api {get} /pc/v2/news/info 新闻详情
     * @apiDescription 新闻详情------------------------------王磊
     * @apiGroup PC
     * @apiPermission 需要验证
     * @apiParam {int}      draft_id         新闻ID
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "keywords": { // 新闻关键词侧边栏
     *                      "keyword": { // 新闻关键词
     *                          "name": "DeepMind", // 新闻关键词中文名
     *                          "name_en": "DeepMind", // 新闻关键词英文名
     *                          "code": "YO2QEELHzE5YonZC", // 新闻关键词code
     *                          "oneword": "神经网络技术研发企业", // 新闻关键词一句话
     *                          "city_info": "英国伦敦", // 新闻关键词城市
     *                          "birth_info": "2010年09月23日", // 新闻关键词时间
     *                          "logo_path": "http://uploads2.b0.upaiyun.com///keywords1/key1490953288.jpg!initial", // 新闻关键词logo
     *                          "head_path": "http://uploads2.b0.upaiyun.com///keywords2/key1490953288.jpg!initial" // 新闻关键词缩略图
     *                      },
     *                      "product": [ // 新闻关键词产品
     *                          {
     *                              "core_id": 128,
     *                              "name": "阿尔法狗"
     *                          }
     *                      ],
     *                      "tech": [ // 新闻关键词科技
     *                          {
     *                              "core_id": 800,
     *                              "name": "深度强化学习方法"
     *                          },
     *                      ],
     *                      "project": [ // 新闻关键词项目
     *                          {
     *                              "core_id": 986,
     *                              "name": "嵌入式学习库"
     *                          },
     *                      ],
     *                      "people": [ // 新闻关键词人物
     *                          {
     *                              "id": 38,
     *                              "keyword_code": "Po44Dk1las6yJ6TA", //新闻关键词人物code
     *                              "name": "David Silver", //新闻关键词人物中文名
     *                              "name_en": "David Silver", //新闻关键词人物英文名
     *                              "role": "CTO" //新闻关键词人物职务
     *                          },
     *                      ]
     *                  }
     *              "news": { // 新闻信息
     *                  "id": 1, // 新闻ID
     *                  "title": "NextVR领衔", // 新闻标题
     *                  "subtitle": "虚拟现实VR直播来袭", // 新闻副标题
     *                  "author": "柳威", // 新闻写稿人
     *                  "email": "13611086916@xincheng.tv" // 写稿人邮箱
     *              },
     *              "newskeywords": [ // 新闻关键词
     *                  {
     *                      "keyword_code": "UBDp4pOVRKf0q2wW",
     *                      "name": "NextVR"
     *                  }
     *              ],
     *              "newsindustrys": [ // 新闻行业
     *                  {
     *                      "industry_code": "IND0020",
     *                      "name": "虚拟现实直播"
     *                  }
     *              ],
     *              "newsintro": [  // 新闻概要
     *                  "根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学、李嘉诚基金会、市环保局负责配合做好此项工作，由汕头市环境保护研究所抽调精干专业技术力量协调配合开展科研项目调查采样，协助Nanospun科技有限公司开展科研项目初步研究、资料收集和地点选择等一系列工作。",
     *                  "凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂。",
     *                  "凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂。"
     *              ],
     *              "newscontent": [（新闻内容，"picture"-图片,"gallery"-图库,"video"-视频, 其他全是文字）
     *                  {
     *                      "element_type": "gallery", // 新闻内容类型 （图库）
     *                      "id": 1, // 图库ID
     *                      "title": "Solar Probe Plus太阳探测器", // 图库标题
     *                      "pictures": [ // 图片
     *                          {
     *                              "desc": "", //图片简述
     *                              "original_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270"   // 图片url
     *                          },
     *                      ]
     *                  },
     *                  {
     *                      "element_type": "video", // 新闻内容类型 （视频）
     *                      "id": 3, // 视频
     *                      "title": "顺丰视频", // 视频标题
     *                      "thumb_path": "http://uploads2.b0.upaiyun.com//gallery/gallery_p1496485065.jpg", // 视频背景
     *                      "url": "XMzA3NDg4NTc2MA" // 视频播放码
     *                  },
     *                  {
     *                      "element_type": "text", // 新闻内容类型 （文字）
     *                      "element_content": "与FACEBOOK旗下的Oculus公司和HTC的VIVE等专注于硬件发展不同的是，来自美国加利福尼亚拉古娜海滩的VR公司 NextVR ，是一家专注于虚拟现实内容产生和制作的公司，准确的来说，就是VR直播。而NextVR的这种定位，与其两位公司创始人的经历是密不可分的。"
     *                  },
     *                  {
     *                      "element_type": "picture",// 新闻内容类型 （图片）
     *                      "id": 2550, // 图片ID
     *                      "desc": "",
     *                      "original_path": "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg"
     *                  }
     *              ],
     *              "key": [（相关关键词信息）
     *                  {
     *                      "code": "UBDp4pOVRKf0q2wW", // 相关关键词code
     *                      "name": "NextVR", // 相关关键词中文名
     *                      "name_en": "NextVR", // 相关关键词英文名
     *                      "oneword": "VR直播服务商", // 相关关键词一句话
     *                      "desc": "NextVR于2009年在加州创立，是一家VR直播服务商，创始人为David Cole、DJ Roller。现任CEO为Dave Cole。" // 相关关键词简介
     *                  }
     *              ]
     *          }
     *      }
     */ 
    public function infos(Request $request)
    {
        $val = $request->all();
        $validator = \Validator::make($val, [
            'draft_id'     => 'required|numeric',
        ]);
        if ($validator->fails()) return $this->responseFromat(501,$validator);
        $newsData = DB::table('db_writing.draft')->leftJoin('sys_user','draft.author','=','sys_user.id')->where(['draft.id'=>$val['draft_id'], 'draft.deleted_at'=>null])->select(['draft.id','title','subtitle','name as author','email'])->first();
        $newskeywords = DB::table('db_writing.draft_keyword')->leftJoin('sys_keyword','draft_keyword.keyword_code','=','sys_keyword.code')->where(['draft_id'=>$val['draft_id'], 'draft_keyword.deleted_at'=>null])->orderBy('draft_keyword.order')->select(['keyword_code','name'])->get();
        $newsindustrys = DB::table('db_writing.draft_industry')->leftJoin('sys_industry','draft_industry.industry_code','=','sys_industry.code')->where(['draft_id'=>$val['draft_id'], 'draft_industry.deleted_at'=>null])->orderBy('draft_industry.order')->select(['industry_code','name'])->get();
        $newscontentData = DB::table('db_writing.draft_body')->where(['draft_id'=>$val['draft_id'], 'draft_body.deleted_at'=>null])->orderBy('element_order')->select(['element_type','element_content','element_id as id'])->get();
        $types = ['picture', 'gallery', 'video'];
        foreach ($newscontentData as $key => $va) {
            if($va->element_type == 'intro') {
                $newsintro[] = $va->element_content;
                unset($newscontentData[$key]);
            }
            if(in_array($va->element_type, $types)) {
                unset($newscontentData[$key]->element_content);
            }
            if($va->element_type == 'picture') {
                $picturedata = DB::table('xc_picture')->where(['id'=>$va->id, 'deleted_at'=>null])->select(['desc','original_path'])->first();
                if($picturedata) {
                    $va->desc = $picturedata->desc;
                    $va->original_path = $picturedata->original_path;
                }
            }
            if($va->element_type == 'gallery') {
                $gallery_content = DB::table('xc_gallery')->where(['id'=>$va->id, 'deleted_at'=>null])->select(['id','title'])->first();
                if($gallery_content) {
                    $va->id = $gallery_content->id;
                    $va->title = $gallery_content->title;
                    $va->pictures = DB::table('xc_picture')->where(['gallery_id'=>$va->id, 'deleted_at'=>null])->select(['desc','original_path'])->limit(8)->get();
                }
            }
            if($va->element_type == 'video') {
                $video_content = DB::table('xc_video')->where(['id'=>$va->id, 'deleted_at'=>null])->select(['title','thumb_path','url'])->first();
                if($video_content) {
                    $va->title = $video_content->title;
                    $va->thumb_path = $video_content->thumb_path;
                    $va->url = $video_content->url;
                }
            }
        }
        foreach($newscontentData as $va) {
            $newscontentDatatwo[] = $va;
        }
        $keyData = DB::table('db_writing.draft_keyword')->leftJoin('sys_keyword','draft_keyword.keyword_code','=','sys_keyword.code')->where(['draft_id'=>$val['draft_id'], 'draft_keyword.deleted_at'=>null])->select(['name','name_en','code','oneword','desc'])->get();
        $data['keywords'] = $this->keyinfo($newskeywords[0]->keyword_code);
        $data['news'] = $newsData;
        $data['newskeywords'] = $newskeywords;
        $data['newsindustrys'] = $newsindustrys;
        $data['news'] = $newsData;
        $data['newsintro'] = empty($newsintro) ? null : $newsintro;
        $data['newscontent'] = $newscontentDatatwo;
        $data['key'] = $keyData;
        return $this->responseFromat(200,$data);
    }


}