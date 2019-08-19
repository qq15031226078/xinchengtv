<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use DB;

class NewsController extends CommonController
{
    
	/**
     * @api {get} /v2/news/details 新闻详情
     * @apiDescription 新闻详情------------------------------王磊
     * @apiGroup Content
     * @apiPermission 需要验证
     * @apiParam {int}	    draft_id	    新闻ID（示例：1,2,3,4,5,6,7）
     * @apiParam {string}	keyword_code	关键词code（示例：UBDp4pOVRKf0q2wW，svC6eXb8uKQoWULI，A9UzNOhBJyO2PGGM，QujrFl7lIYOxssWM，YO2QEELHzE5YonZC，8sSeNaK0mxkd1fVB，Ezo81Xlf4wdNwe3K）
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "keywords": { // 主关键词信息
     *                  "code": "qZ7i5UH1hMS5prC8", // 关键词code
     *                  "name": "拉里·佩奇",  // 关键词中文名
     *                  "name_en": "Larry Page", // 关键词英文名
     *                  "oneword": "Google联合创始人", // 关键词一句话描述
     *                  "desc": "Google公司创始人之一，2011年4月4日任谷歌CEO ，现任Alphabet公司CEO。", // 关键词简介
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1496801733.jpg!initial", // 关键词缩略图
     *                  "head_path": "" // 关键词头图
     *                  "birth_info": "创立于：2009年" // 关键词城市时间信息
     *              },
     *              "news": { // 新闻信息
     *                  "id": 1, // 新闻ID
     *                  "title": "NextVR领衔", // 新闻标题
     *                  "subtitle": "虚拟现实VR直播来袭", // 新闻副标题
     *                  "author": "柳威", // 新闻写稿人
     *                  "email": "13611086916@xincheng.tv" // 写稿人邮箱
     *              },
     *              "newsintro": // 新闻概要  "根据汕头市人民政府工作安排，Nanospun纳米黄金笼子污水处理项目由徐凯副市长牵头，汕头大学、李嘉诚基金会、市环保局负责配合做好此项工作，由汕头市环境保护研究所抽调精干专业技术力量协调配合开展科研项目调查采样，协助Nanospun科技有限公司开展科研项目初步研究、资料收集和地点选择等一系列工作；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂；凤凰科技讯9月29日消息，根据台湾媒体报道，一iPhone 8 Plus女性用户在充电过程中发生意外，该苹果手机的前面板明显开裂",
     *              "newscontent": [（新闻内容，"picture"-图片,"gallery"-图库,"video"-视频, 其他全是文字）
     *                  {
     *                      "element_type": "gallery", // 新闻内容类型 （图库）
     *                      "id": 1, // 图库ID
     *                      "title": "Solar Probe Plus太阳探测器", // 图库标题
     *                      "pictures": [ // 图片
     *                          {
     *                              "desc": "", //图片简述
     *                              "original_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270"   // 大图片url
     *                              "thumb_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270"   // 缩略图片url
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
     *                      "original_path": "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg",大图
     *                      "thumb_path": "http://uploads2.b0.upaiyun.com///news/news1506477562.jpg",缩略图
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
     public function getnewsdetails(Request $req)
     {
    	$val = $req->all();
    	if (empty($val['draft_id']) || empty($val['keyword_code'])) return $this->responseFromat(4300,[],'缺少参数！');
    	$keywordData = $this->keywordInfo($val['keyword_code']);
        $newsData = DB::table('db_writing.draft')->leftjoin('sys_user','draft.author','=','sys_user.id')->where(['draft.id'=>$val['draft_id'], 'draft.deleted_at'=>null])->select(['draft.id','title','subtitle','name as author','email'])->first();
    	$newscontentData = DB::table('db_writing.draft_body')->where(['draft_id'=>$val['draft_id'], 'deleted_at'=>null])->orderBy('element_order')->select(['element_type','element_content','element_id as id'])->get();
        $types = ['picture', 'gallery', 'video'];
        $newsintro = [];
    	foreach ($newscontentData as $key => $va) {
            if($va->element_type == 'intro') {
                $newsintro[] = $va->element_content;
                unset($newscontentData[$key]);
            }
            if(in_array($va->element_type, $types)) {
                unset($newscontentData[$key]->element_content);
            }
            if($va->element_type == 'picture') {
                $picturedata = DB::table('xc_picture')->where(['id'=>$va->id, 'deleted_at'=>null])->select(['desc','original_path','thumb_path'])->first();
                if($picturedata) {
                    $va->desc = $picturedata->desc;
                    $va->thumb_path = $picturedata->thumb_path;
                    $va->original_path = $picturedata->original_path;
                }
            }
            if($va->element_type == 'gallery') {
                $gallery_content = DB::table('xc_gallery')->where(['id'=>$va->id, 'deleted_at'=>null])->select(['id','title'])->first();
                if($gallery_content) {
                    $va->id = $gallery_content->id;
                    $va->title = $gallery_content->title;
                    $va->pictures = DB::table('xc_picture')->where(['gallery_id'=>$va->id, 'deleted_at'=>null])->select(['desc','original_path','thumb_path'])->limit(8)->get();
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
        $keyData = DB::table('db_writing.draft_keyword')->leftjoin('sys_keyword','draft_keyword.keyword_code','=','sys_keyword.code')->where(['draft_id'=>$val['draft_id'], 'draft_keyword.deleted_at'=>null])->select(['name','name_en','code','oneword','desc'])->get();
        $data['keywords'] = $keywordData;
        $data['news'] = $newsData;
        $data['newsintro'] = $this->transformation($newsintro);
        $data['newscontent'] = $newscontentDatatwo;
        $data['key'] = $keyData;
        return $this->responseFromat(200,$data);
    }

    /**
     * @api {get} /v2/news/thumb/{draft_id} 新闻缩略图（分享）
     * @apiDescription 新闻缩略图（分享） ———— 朱朔男
     * @apiGroup Content
     * @apiPermission 需要验证
     * @apiParam {int}	    draft_id	    新闻ID（示例：1,2,3,4,5,6,7）
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "thumb_path": "https://uploads2.b0.upaiyun.com///news/news1490344133.jpg!news.logo.500.270"
     *          }
     *      }
     */
     public function getnewsthumb($draft_id)
     {
    	if (empty($draft_id)) return $this->responseFromat(530,[],'缺少keyword_code参数');
        $thumb_path = DB::table('db_writing.draft')->where(['id'=>$draft_id])->select(['thumb_path'])->first();
        $data['thumb_path'] = $thumb_path->thumb_path;
        return $this->responseFromat(200,$data);
    }
}
