<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Corerecord;
use App\Models\KeyWordBlock;
use App\Models\KeyWordBlockInfo;
use App\Transformers\KeywordTransformer;
use Carbon\Carbon;
use App\Models\Draft;
use App\Models\DraftIndustry;
use App\Models\DraftKeyword;
use App\Models\DraftIntro;
use App\Models\DraftBody;
use App\Models\Industry;
use App\Models\XcContent;
use App\Models\Recommend;
use App\Models\Video;
use App\Models\Tv;
use App\Models\Geo;
use App\Models\Type;
use App\Models\Gallery;
use App\Models\Picture;
use App\Models\Financing;
use App\Models\Keywords;
use App\Models\Management;
use App\Models\Corevalueindex;
use App\Models\KeywordIndustryRef;
use App\Models\Corevalue;
use App\Models\Subcom;
use App\Models\Program;
use App\Utils\InsideConnect;
use Illuminate\Http\Request;

use App\Transformers\ContentTransformer;
use App\Transformers\CorevalueIndexTransformer;
use App\Transformers\ProgramTransformer;

class IndexController extends BaseController
{
    function __construct(Tv $tv, Type $type, Geo $xcgeo, Recommend $recommend, Financing $financing, Subcom $subcom, Management $management, DraftKeyword $draftKeyword, KeywordIndustryRef $keywordindustryref, Program $xcprogram, Corevalue $corevalue, Corevalueindex $corevalueindex, XcContent $content, Draft $draft, Video $video, Gallery $gallery, Industry $industry, Keywords $keywords, DraftIntro $draftintro, DraftBody $draftbody, Picture $picture, Corerecord $corerecord, KeyWordBlock $keyWordBlock, KeyWordBlockInfo $keyWordBlockInfo)
    {
        $this->content = $content;
        $this->type = $type;
        $this->tv = $tv;
        $this->subcom = $subcom;
        $this->xcgeo = $xcgeo;
        $this->recommend = $recommend;
        $this->financing = $financing;
        $this->management = $management;
        $this->draftKeyword = $draftKeyword;
        $this->keywordindustryref = $keywordindustryref;
        $this->xcprogram = $xcprogram;
        $this->draft = $draft;
        $this->draftintro = $draftintro;
        $this->draftbody = $draftbody;
        $this->video = $video;
        $this->gallery = $gallery;
        $this->picture = $picture;
        $this->industry = $industry;
        $this->keywords = $keywords;
        $this->corevalue = $corevalue;
        $this->corevalueindex = $corevalueindex;
        $this->corerecord = $corerecord;
        $this->key_word_block = $keyWordBlock;
        $this->keywordblockinfo = $keyWordBlockInfo;
        $this->res = array(
            'status_code' => 200,
            'message' => null,
            'data' => null
        );
    }

    /**
     * @api {get} /getindexinfolist 获取行业最新资讯
     * @apiDescription 获取行业最新资讯
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiParam {Int}   limit     单页条数
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getindexinfolist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *"status_code": 200,
     *"message": null,
     *"data": {
     *    "exception": null,
     *    "headers": {},
     *    "original": {
     *        "total": 3,
     *        "per_page": "1",
     *        "current_page": 1,
     *        "last_page": 3,
     *        "next_page_url": "http://127.0.0.1:8094/api/getindexinfolist?page=2",
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 1,
     *        "data": [
     *            {
     *                "id": 17,
     *                "news_id": 0,
     *                "video_id": 163,
     *                "gallery_id": 0,
     *                "keyword_code": "KW3316",
     *                "industry_code": "",
     *                "is_main": 0,
     *                "position": 1,
     *                "project_id": 0,
     *                "product_id": 0,
     *                "tech_id": 0,
     *                "event_id": 0,
     *                "order": 0,
     *                "created_at": "2017-06-27 04:46:30",
     *                "industry_info": null,
     *                "keyword_info": null,
     *                "news_info": null,
     *                "video_info": {
     *                    "id": 163,
     *                    "title": "321",
     *                    "subtitle": "321321",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Wn7uPUNWmb.jpeg"
     *                },
     *                "gallery_info": null
     *            }
     *        ]
     *    }
     *}
     *}
     */
    public function getIndexInfoList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_code_list = explode(",", $req->get('industry_code_list'));
        if (!is_array($industry_code_list)) {
            $this->res['status_code'] = 201;
            $this->res['message'] = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }
        $data = $this->content->where(['position' => 2])
            ->orWhere('position', 3)
            ->whereIn('industry_code', $industry_code_list)->where(function ($query) {
                $query->orWhere('video_id', '!=', 0)
                    ->orWhere('news_id', '!=', 0);
            })->groupBy('news_id')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        foreach ($data as $key => $value) {
            $data[$key]['industry_info'] = null;
            $data[$key]['keyword_info'] = null;
            $data[$key]['news_info'] = null;
            $data[$key]['video_info'] = null;
            $data[$key]['gallery_info'] = null;

            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code' => $value['industry_code']), ['id', 'parent', 'name', 'name_en']);
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }
            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en', 'desc']);
            }
            if ($value['news_id']) {
                $data[$key]['news_info'] = $this->draft->find($value['news_id'], ['id', 'title', 'subtitle', 'thumb_path']);
            }

            if ($value['video_id']) {
                $data[$key]['video_info'] = $this->video->find($value['video_id'], ['id', 'title', 'subtitle', 'thumb_path', 'url']);
            }

            if ($value['gallery_id']) {
                $data[$key]['gallery_info'] = $this->gallery->find($value['gallery_id'], ['id', 'title', 'subtitle', 'thumb_path']);
            }
        }

        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getIndustryIndexInfoList 获取父级下的行业最新资讯
     * @apiDescription 获取父级下的行业最新资讯
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {string} industry_parent_code   父级行业Code
     * @apiParam {Int}   limit     单页条数
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getIndustryIndexInfoList
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *"status_code": 200,
     *"message": null,
     *"data": {
     *    "exception": null,
     *    "headers": {},
     *    "original": {
     *        "total": 3,
     *        "per_page": "1",
     *        "current_page": 1,
     *        "last_page": 3,
     *        "next_page_url": "http://127.0.0.1:8094/api/getIndustryIndexInfoList?page=2",
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 1,
     *        "data": [
     *            {
     *                "id": 17,
     *                "news_id": 0,
     *                "video_id": 163,
     *                "gallery_id": 0,
     *                "keyword_code": "KW3316",
     *                "industry_code": "",
     *                "is_main": 0,
     *                "position": 1,
     *                "project_id": 0,
     *                "product_id": 0,
     *                "tech_id": 0,
     *                "event_id": 0,
     *                "order": 0,
     *                "created_at": "2017-06-27 04:46:30",
     *                "industry_info": null,
     *                "keyword_info": null,
     *                "news_info": null,
     *                "video_info": {
     *                    "id": 163,
     *                    "title": "321",
     *                    "subtitle": "321321",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Wn7uPUNWmb.jpeg"
     *                },
     *                "gallery_info": null
     *            }
     *        ]
     *    }
     *}
     *}
     */

    public function getIndustryIndexInfoList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');

        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }
        $data = $this->content->where(['position' => 2])
            ->orWhere('position', 3)
            ->whereIn('industry_code', $industry_list)->where(function ($query) {
                $query->orWhere('video_id', '!=', 0)
                    ->orWhere('news_id', '!=', 0);
            })->groupBy('news_id')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            $data[$key]['industry_info'] = null;
            $data[$key]['keyword_info'] = null;
            $data[$key]['news_info'] = null;
            $data[$key]['video_info'] = null;
            $data[$key]['gallery_info'] = null;

            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code' => $value['industry_code']), ['id', 'parent', 'name', 'name_en']);
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }
            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en', 'desc']);
            }
            if ($value['news_id']) {
                $data[$key]['news_info'] = $this->draft->find($value['news_id'], ['id', 'title', 'subtitle', 'thumb_path']);
            }

            if ($value['video_id']) {
                $data[$key]['video_info'] = $this->video->find($value['video_id'], ['id', 'title', 'subtitle', 'thumb_path', 'url']);
            }

            if ($value['gallery_id']) {
                $data[$key]['gallery_info'] = $this->gallery->find($value['gallery_id'], ['id', 'title', 'subtitle', 'thumb_path']);
            }
        }

        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /getindexlatestinfo 获取首页最新资讯
     * @apiDescription 获取首页最新资讯
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getindexlatestinfo
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *"status_code": 200,
     *"message": null,
     *"data": {
     *    "exception": null,
     *    "headers": {},
     *    "original": {
     *        "total": 3,
     *        "per_page": "1",
     *        "current_page": 1,
     *        "last_page": 3,
     *        "next_page_url": "http://127.0.0.1:8094/api/getindexlatestinfo?page=2",
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 1,
     *        "data": [
     *            {
     *                "id": 17,
     *                "news_id": 0,
     *                "video_id": 163,
     *                "gallery_id": 0,
     *                "keyword_code": "KW3316",
     *                "industry_code": "",
     *                "is_main": 0,
     *                "position": 1,
     *                "project_id": 0,
     *                "product_id": 0,
     *                "tech_id": 0,
     *                "event_id": 0,
     *                "order": 0,
     *                "created_at": "2017-06-27 04:46:30",
     *                "industry_info": null,
     *                "keyword_info": null,
     *                "news_info": null,
     *                "video_info": {
     *                    "id": 163,
     *                    "title": "321",
     *                    "subtitle": "321321",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Wn7uPUNWmb.jpeg"
     *                },
     *                "gallery_info": null
     *            }
     *        ]
     *    }
     *}
     *}
     */
    public function getIndexLatestInfo(Request $req)
    {
        $limit = $req->get('limit', 6);

        $data = $this->content->where(['position' => 1])->orWhere('position', 3)->where(function ($query) {
            $query->orWhere('video_id', '!=', 0)
                ->orWhere('news_id', '!=', 0);
        })->groupBy('news_id')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            $data[$key]['industry_info'] = null;
            $data[$key]['keyword_info'] = null;
            $data[$key]['news_info'] = null;
            $data[$key]['video_info'] = null;
            $data[$key]['gallery_info'] = null;

            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code' => $value['industry_code']), ['id', 'parent', 'name', 'name_en']);
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }

            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en', 'desc']);
            }

            if ($value['news_id']) {
                $data[$key]['news_info'] = $this->draft->find($value['news_id'], ['id', 'title', 'subtitle', 'thumb_path']);
            }

            if ($value['video_id']) {
                $data[$key]['video_info'] = $this->video->find($value['video_id'], ['id', 'title', 'subtitle', 'thumb_path']);

            }

            if ($value['gallery_id']) {
                $data[$key]['gallery_info'] = $this->gallery->find($value['gallery_id'], ['id', 'title', 'subtitle', 'thumb_path']);
            }

        }

        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getxinchengindexlatestinfo 获取首页新城商业接口
     * @apiDescription 获取首页新城商业接口
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxinchengindexlatestinfo
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *"status_code": 200,
     *"message": null,
     *"data": {
     *    "exception": null,
     *    "headers": {},
     *    "original": {
     *        "total": 3,
     *        "per_page": "1",
     *        "current_page": 1,
     *        "last_page": 3,
     *        "next_page_url": "http://127.0.0.1:8094/api/getxinchengindexlatestinfo?page=2",
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 1,
     *        "data": [
     *            {
     *                "id": 17,
     *                "news_id": 0,
     *                "video_id": 163,
     *                "gallery_id": 0,
     *                "keyword_code": "KW3316",
     *                "industry_code": "",
     *                "is_main": 0,
     *                "position": 1,
     *                "project_id": 0,
     *                "product_id": 0,
     *                "tech_id": 0,
     *                "event_id": 0,
     *                "order": 0,
     *                "created_at": "2017-06-27 04:46:30",
     *                "industry_info": null,
     *                "keyword_info": null,
     *                "news_info": null,
     *                "video_info": {
     *                    "id": 163,
     *                    "title": "321",
     *                    "subtitle": "321321",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Wn7uPUNWmb.jpeg"
     *                },
     *                "gallery_info": null
     *            }
     *        ]
     *    }
     *}
     *}
     */

    public function getXinchengIndexLatestInfo(Request $req)
    {
        $limit = $req->get('limit', 6);

        $data = $this->xcprogram->where(['program_type' => 0, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->orderBy('created_at', 'desc')
            ->paginate($limit);
        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /gethomeprogramm 首页节目
     * @apiDescription 首页节目
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     像素科技条数
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/gethomeprogramm
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "total": 0,
     *            "per_page": 6,
     *            "current_page": 1,
     *            "last_page": 0,
     *            "next_page_url": null,
     *            "prev_page_url": null,
     *            "from": 1,
     *            "to": 2,
     *            "data": {
     *                "xincheng_list": [
     *                    {
     *                        "id": 5,
     *                        "program_type": 0,
     *                        "title": "测试1111",
     *                        "subtitle": "",
     *                        "issue": 111,
     *                        "thumb_path": "www.xincheng.tv",
     *                        "type_code_website": "ST00002",
     *                        "link": "www.baidu.com",
     *                        "is_passed": 1,
     *                        "is_published": 1,
     *                        "is_disabled": 0,
     *                        "is_locked": 0,
     *                        "order": 0,
     *                        "created_at": null
     *                    },
     *                    {
     *                        "id": 6,
     *                        "program_type": 0,
     *                        "title": "测试11113",
     *                        "subtitle": "",
     *                        "issue": 111,
     *                        "thumb_path": "www.xincheng.tv",
     *                        "type_code_website": "ST00002",
     *                        "link": "www.baidu.com",
     *                        "is_passed": 1,
     *                        "is_published": 1,
     *                        "is_disabled": 0,
     *                        "is_locked": 0,
     *                        "order": 0,
     *                        "created_at": null
     *                    }
     *                ],
     *                "xiangshu_list": [
     *                    {
     *                        "id": 9,
     *                        "program_type": 1,
     *                        "title": "商业你好",
     *                        "subtitle": "",
     *                        "issue": 1,
     *                        "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-jLKQHXUzlD.jpeg",
     *                        "type_code_website": "ST00003",
     *                        "link": "zxczx",
     *                        "is_passed": 1,
     *                        "is_published": 1,
     *                        "is_disabled": 0,
     *                        "is_locked": 0,
     *                        "order": 0,
     *                        "created_at": null
     *                    },
     *                    {
     *                        "id": 10,
     *                        "program_type": 1,
     *                        "title": "商业你好aaa",
     *                        "subtitle": "",
     *                        "issue": 1,
     *                        "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-jLKQHXUzlD.jpeg",
     *                        "type_code_website": "ST00003",
     *                        "link": "zxczx",
     *                        "is_passed": 1,
     *                        "is_published": 1,
     *                        "is_disabled": 0,
     *                        "is_locked": 0,
     *                        "order": 0,
     *                        "created_at": null
     *                    }
     *                ]
     *            }
     *        }
     *    }
     *}
     */
    public function GetHomeProgramm(Request $req)
    {
        $limit = $req->get('limit', 4);

        $data = $this->xcprogram->where(['program_type' => 4, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->orderBy('created_at', 'desc')->paginate(6);

        $xincheng = $this->xcprogram->where(['program_type' => 0, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->orderBy('created_at', 'desc')->limit(2)->get();
        $xiangshukeji = $this->xcprogram->where(['program_type' => 1, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->orderBy('created_at', 'desc')->limit($limit)->get();
        $data['xincheng_list'] = $xincheng;
        $data['xiangshu_list'] = $xiangshukeji;
        $this->res['data'] = $this->response->paginator($data, new ProgramTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /getxinchengpromotelist 获取播放列表新城商业接口
     * @apiDescription 获取播放列表新城商业接口
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {Int}   video_id     视频ID
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxinchengpromotelist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *"status_code": 200,
     *"message": null,
     *"data": {
     *    "exception": null,
     *    "headers": {},
     *    "original": {
     *        "total": 3,
     *        "per_page": "1",
     *        "current_page": 1,
     *        "last_page": 3,
     *        "next_page_url": "http://127.0.0.1:8094/api/getxinchengpromotelist?page=2",
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 1,
     *        "data": [
     *            {
     *                "id": 17,
     *                "news_id": 0,
     *                "video_id": 163,
     *                "gallery_id": 0,
     *                "keyword_code": "KW3316",
     *                "industry_code": "",
     *                "is_main": 0,
     *                "position": 1,
     *                "project_id": 0,
     *                "product_id": 0,
     *                "tech_id": 0,
     *                "event_id": 0,
     *                "order": 0,
     *                "created_at": "2017-06-27 04:46:30",
     *                "industry_info": null,
     *                "keyword_info": null,
     *                "news_info": null,
     *                "video_info": {
     *                    "id": 163,
     *                    "title": "321",
     *                    "subtitle": "321321",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Wn7uPUNWmb.jpeg"
     *                },
     *                "gallery_info": null
     *            }
     *        ]
     *    }
     *}
     *}
     */
    public function getXinchengPromoteList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $video_id = $req->get('video_id');
        $data = $this->xcprogram->where(['program_type' => 0, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->where('id', '!=', $video_id)->orderBy('created_at', 'desc')->paginate($limit);
        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);

    }

    /**
     * @api {get} /getxinchengtvindexlatestinfo 获取首页新城TV接口
     * @apiDescription 获取首页新城TV接口
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxinchengtvindexlatestinfo
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "current_page": 1,
     *            "data": [
     *                {
     *                    "id": 4,
     *                    "title": "杀菌灯",
     *                    "subtitle": "都看见爱上",
     *                    "type_code": "MUSIC",
     *                    "type_code_website": "ST00002",
     *                    "link": "www。baidu.com",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-0iUUbElPyh.jpeg",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "ondate": 2017,
     *                    "created_at": "2017-09-19 14:29:11"
     *                },
     *                {
     *                    "id": 3,
     *                    "title": "合适的",
     *                    "subtitle": "胜多负少",
     *                    "type_code": "MUSIC",
     *                    "type_code_website": "ST00002",
     *                    "link": "www.baidu.com",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-1PnYK7inB4.jpeg",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "ondate": 2017,
     *                    "created_at": "2017-09-19 14:28:30"
     *                },
     *                {
     *                    "id": 1,
     *                    "title": "测试节目",
     *                    "subtitle": "测试节目副标题",
     *                    "type_code": "MUSIC",
     *                    "type_code_website": "ST00002",
     *                    "link": "www.baidu.com",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-RRYsF35org.jpeg",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "ondate": 2017,
     *                    "created_at": "2017-09-12 16:41:52"
     *                }
     *            ],
     *            "from": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "path": "http://127.0.0.1:8094/api/getxinchengtvindexlatestinfo",
     *            "per_page": 6,
     *            "prev_page_url": null,
     *            "to": 3,
     *            "total": 3
     *        }
     *    }
     *}
     */
    public function getXinchengTVIndexLatestInfo(Request $req)
    {
        $limit = $req->get('limit', 6);

        // $data = $this->xcprogram->where(['program_type'=>2,'is_passed'=>1,'is_published'=>1,'is_disabled'=>0])->orderBy('created_at', 'desc')
        // 					  	->paginate($limit);
        $data = $this->tv->where(['is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->orderBy('order', 'asc')->orderBy('created_at', 'desc')
            ->paginate($limit);
        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getxinchengtvpromotelist 获取播放列表新城TV接口
     * @apiDescription 获取播放列表新城TV接口
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {Int}   tv_id     视频ID
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxinchengtvpromotelist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "current_page": 1,
     *            "data": [
     *                {
     *                    "id": 4,
     *                    "title": "杀菌灯",
     *                    "subtitle": "都看见爱上",
     *                    "type_code": "MUSIC",
     *                    "type_code_website": "ST00002",
     *                    "link": "www。baidu.com",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-0iUUbElPyh.jpeg",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "ondate": 2017,
     *                    "created_at": "2017-09-19 14:29:11"
     *                },
     *                {
     *                    "id": 3,
     *                    "title": "合适的",
     *                    "subtitle": "胜多负少",
     *                    "type_code": "MUSIC",
     *                    "type_code_website": "ST00002",
     *                    "link": "www.baidu.com",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-1PnYK7inB4.jpeg",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "ondate": 2017,
     *                    "created_at": "2017-09-19 14:28:30"
     *                }
     *            ],
     *            "from": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "path": "http://127.0.0.1:8094/api/getxinchengtvpromotelist",
     *            "per_page": 6,
     *            "prev_page_url": null,
     *            "to": 2,
     *            "total": 3
     *        }
     *    }
     *}
     */
    public function getXinchengTVPromoteList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $tv_id = $req->get('tv_id', 0);
        $data = $this->tv->where(['is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->where('id', '!=', $tv_id)->orderBy('order', 'asc')->orderBy('created_at', 'desc')
            ->paginate($limit);
        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getxiangsuindexlatestinfo 获取首页像素科技口
     * @apiDescription 获取首页像素科技口
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxiangsuindexlatestinfo
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "total": 2,
     *            "per_page": 6,
     *            "current_page": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "prev_page_url": null,
     *            "from": 1,
     *            "to": 2,
     *            "data": [
     *                {
     *                    "id": 9,
     *                    "program_type": 1,
     *                    "title": "商业你好",
     *                    "subtitle": "",
     *                    "issue": 1,
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-jLKQHXUzlD.jpeg",
     *                    "type_code_website": "ST00003",
     *                    "link": "zxczx",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "created_at": null
     *                },
     *                {
     *                    "id": 10,
     *                    "program_type": 1,
     *                    "title": "商业你好aaa",
     *                    "subtitle": "",
     *                    "issue": 1,
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-jLKQHXUzlD.jpeg",
     *                    "type_code_website": "ST00003",
     *                    "link": "zxczx",
     *                    "is_passed": 1,
     *                    "is_published": 1,
     *                    "is_disabled": 0,
     *                    "is_locked": 0,
     *                    "order": 0,
     *                    "created_at": null
     *                }
     *            ]
     *        }
     *    }
     *}
     */

    public function getXiangsuIndexLatestInfo(Request $req)
    {
        $limit = $req->get('limit', 6);

        $data = $this->xcprogram->where(['program_type' => 1, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->orderBy('created_at', 'desc')
            ->paginate($limit);

        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getxiangsulist 当前 像素科技 新城商业播放地址
     * @apiDescription 当前 像素科技 新城商业播放地址
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   video_id     视频ID
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxiangsulist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.cp+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     * {
     *     "status_code": 200,
     *     "message": null,
     *     "data": {
     *         "id": 9,
     *         "program_type": 1,
     *         "title": "商业你好",
     *         "subtitle": "",
     *         "issue": 1,
     *         "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-jLKQHXUzlD.jpeg",
     *         "type_code_website": "ST00003",
     *         "link": "zxczx",
     *         "is_passed": 1,
     *         "is_published": 1,
     *         "is_disabled": 0,
     *         "is_locked": 0,
     *         "order": 0,
     *         "created_at": "2017-09-21 13:32:20"
     *     }
     * }
     */

    public function getXiangsuList(Request $req)
    {
        $video_id = $req->get('video_id');
        $data = $this->xcprogram->where(['id' => $video_id])->first();
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /gettvlist 当前新城tv 播放地址
     * @apiDescription 当前新城tv 播放地址
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   tv_id     TVID
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/gettvlist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.cp+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     * {
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "id": 3,
     *        "title": "合适的",
     *        "subtitle": "胜多负少",
     *        "type_code": "MUSIC",
     *        "type_code_website": "ST00002",
     *        "link": "www.baidu.com",
     *        "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-1PnYK7inB4.jpeg",
     *        "is_passed": 1,
     *        "is_published": 1,
     *        "is_disabled": 0,
     *        "is_locked": 0,
     *        "order": 0,
     *        "ondate": 2017,
     *        "created_at": "2017-09-19 14:28:30"
     *    }
     *}
     */
    public function getTvList(Request $req)
    {
        $tv_id = $req->get('tv_id');
        $data = $data = $this->tv->where(['id' => $tv_id])->first();
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /getxiangsupromotelist 获取播放列表像素科技接口
     * @apiDescription 获取播放列表像素科技接口
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {Int}   video_id     视频ID
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getxiangsupromotelist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *"status_code": 200,
     *"message": null,
     *"data": {
     *    "exception": null,
     *    "headers": {},
     *    "original": {
     *        "total": 3,
     *        "per_page": "1",
     *        "current_page": 1,
     *        "last_page": 3,
     *        "next_page_url": "http://127.0.0.1:8094/api/getxiangsupromotelist?page=2",
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 1,
     *        "data": [
     *            {
     *                "id": 17,
     *                "news_id": 0,
     *                "video_id": 163,
     *                "gallery_id": 0,
     *                "keyword_code": "KW3316",
     *                "industry_code": "",
     *                "is_main": 0,
     *                "position": 1,
     *                "project_id": 0,
     *                "product_id": 0,
     *                "tech_id": 0,
     *                "event_id": 0,
     *                "order": 0,
     *                "created_at": "2017-06-27 04:46:30",
     *                "industry_info": null,
     *                "keyword_info": null,
     *                "news_info": null,
     *                "video_info": {
     *                    "id": 163,
     *                    "title": "321",
     *                    "subtitle": "321321",
     *                    "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Wn7uPUNWmb.jpeg"
     *                },
     *                "gallery_info": null
     *            }
     *        ]
     *    }
     *}
     *}
     */
    public function getXiangsuPromoteList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $video_id = $req->get('video_id', 0);
        $data = $this->xcprogram->where(['program_type' => 1, 'is_passed' => 1, 'is_published' => 1, 'is_disabled' => 0])->where('id', '!=', $video_id)->orderBy('created_at', 'desc')->paginate($limit);
        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getproductlist 获取产品首页
     * @apiDescription 获取产品首页
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getproductlist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     * {
     * "status_code": 200,
     * "message": null,
     * "data": {
     * "exception": null,
     * "headers": {},
     * "original": {
     * "current_page": 1,
     * "data": [
     * {
     * "name": "定型声爆验证计划",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1499937133.jpg!ptpe.img",
     * "keyword_code": "EaRNtjTJgLhzpviS",
     * "industry_code": "IND0023",
     * "keyword_name": "美国国家航空航天局",
     * "industry_name": "太空探索"
     * },
     * {
     * "name": "航空地平线",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1499936679.jpg!ptpe.img",
     * "keyword_code": "EaRNtjTJgLhzpviS",
     * "industry_code": "IND0023",
     * "keyword_name": "美国国家航空航天局",
     * "industry_name": "太空探索"
     * },
     * {
     * "name": "太阳能无人机网络服务",
     * "name_en": "Aquila",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500262320.jpg!ptpe.img",
     * "keyword_code": "E6oqVE1ikj7rismu",
     * "industry_code": "IND0018",
     * "keyword_name": "脸书",
     * "industry_name": "互联网"
     * },
     * {
     * "name": "IBM量子计算小组",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500293120.jpg!ptpe.img",
     * "keyword_code": "H5MZVldKMYV4ccol",
     * "industry_code": "IND0012",
     * "keyword_name": "国际商业机器公司",
     * "industry_name": "互联网"
     * },
     * {
     * "name": "Open Blockchain 解决方案",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500293070.jpg!ptpe.img",
     * "keyword_code": "H5MZVldKMYV4ccol",
     * "industry_code": "IND0012",
     * "keyword_name": "国际商业机器公司",
     * "industry_name": "互联网"
     * },
     * {
     * "name": "锯齿湖",
     * "name_en": "Sawtooth Lake",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500087911.jpg!ptpe.img",
     * "keyword_code": "dh0dVwDPE0zJRE4M",
     * "industry_code": "IND0041",
     * "keyword_name": "英特尔",
     * "industry_name": "人工智能"
     * },
     * {
     * "name": "USC-洛克希德·马丁公司量子计算中心",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500272107.jpg!ptpe.img",
     * "keyword_code": "cDvAIRWlbk6wfqr6",
     * "industry_code": "IND0046",
     * "keyword_name": "洛克希德·马丁",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "无线网络",
     * "name_en": "Project Loon",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500273576.jpg!ptpe.img",
     * "keyword_code": "WmWHdKAHHarW238G",
     * "industry_code": "IND0026",
     * "keyword_name": "Alphabet X",
     * "industry_name": "增强现实"
     * },
     * {
     * "name": "掌上电脑",
     * "name_en": "Project Shield",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500279083.jpg!ptpe.img",
     * "keyword_code": "JvOs72RtEPuNCS7K",
     * "industry_code": "IND0015",
     * "keyword_name": "Jigsaw",
     * "industry_name": "互联网"
     * },
     * {
     * "name": "量子架构和计算组",
     * "name_en": "QuArC",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500118377.jpg!ptpe.img",
     * "keyword_code": "nL9TFlR4CFDcUY6U",
     * "industry_code": "IND0014",
     * "keyword_name": "微软",
     * "industry_name": "互联网"
     * },
     * {
     * "name": "Hyperloop One 测试",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1500022042.jpg!ptpe.img",
     * "keyword_code": "4JvuFhZ8hWDHBeCE",
     * "industry_code": "IND0047",
     * "keyword_name": "Hyperloop One",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "军用微型无人机",
     * "name_en": "CICADA",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1501815815.jpg!ptpe.img",
     * "keyword_code": "KNHsOkJLoUxrymjo",
     * "industry_code": "IND0045",
     * "keyword_name": "美国海军实验室",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "3D打印潜艇",
     * "name_en": "Optionally Manned Technology Demonstrator",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1501840976.jpg!ptpe.img",
     * "keyword_code": "ytearo0pd5hewRmN",
     * "industry_code": "IND0069",
     * "keyword_name": "橡树岭国家实验室",
     * "industry_name": "能源"
     * },
     * {
     * "name": "无人驾驶飞行出租车",
     * "name_en": "Vahana",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1504056126.jpg!ptpe.img",
     * "keyword_code": "MVen5yrpZedrRksL",
     * "industry_code": "IND0065",
     * "keyword_name": "空中客车",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "无人驾驶汽车",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///pj/pj1504231873.jpg!ptpe.img",
     * "keyword_code": "5GLULg6uCpHAk0XT",
     * "industry_code": "IND0072",
     * "keyword_name": "苹果",
     * "industry_name": "互联网"
     * }
     * ],
     * "from": 1,
     * "last_page": 2,
     * "next_page_url": "http://xinchengapp.com/api/getproductlist?page=2",
     * "path": "http://xinchengapp.com/api/getproductlist",
     * "per_page": 15,
     * "prev_page_url": null,
     * "to": 15,
     * "total": 19
     * }
     * }
     * }
     *}
     */
    public function getProductList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'industry_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $industry = $this->industry->where('code', $request->input('industry_code'))->first();
        if (!empty($industry)) {
            if ($industry->parent == '') {
//                return $this->corevalueindex->where('industry_code',$industry->code)->pluck('product_id');
                $code = $this->industry->where('parent', $industry->code)->pluck('code');
                $project_id = $this->corevalueindex->whereIn('industry_code', $code)->pluck('project_id');
                $corevalue = $this->corevalue->whereIn('xc_core_value.id', $project_id)->select('xc_core_value.id', 'xc_core_value.name', 'xc_core_value.order', 'xc_core_value.name_en', 'xc_core_value.thumb_path', 'xc_core_value.keyword_code', 'xc_core_value.industry_code', 'sys_keyword.name as keyword_name')
                    ->leftjoin('sys_keyword', 'xc_core_value.keyword_code', '=', 'sys_keyword.code')
                    ->orderBy('sys_keyword.id', 'DESC')
                    ->paginate();
                foreach ($corevalue as &$item) {
                    $corevalue_industry = $this->industry->where('code', $item->industry_code)->first();
                    if ($corevalue_industry->parent == '') {
                        $item->industry_name = $corevalue_industry->name;
                    } else {
                        $item->industry_name = $this->industry->where('code', $corevalue_industry->parent)->first()->name;
                    }
                }
                $this->res['data'] = $this->response->paginator($corevalue, new CorevalueIndexTransformer());
                return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
            }
        }
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);


//		$limit = $req->get('limit', 6);
//		$industry_code_list = explode(",",$req->get('industry_code_list'));
//		if(!is_array($industry_code_list)){
//			$this->res['status_code'] = 201;
//			$this->res['message']    = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
//			return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
//		}

//		$data = $this->corevalueindex->where('product_id','!=',0)
//									 ->whereIn('industry_code',$industry_code_list)
//									 ->orderBy('created_at', 'desc')
//									 ->paginate($limit);
//		 foreach ($data as $key => $value) {
//		 	if($value['industry_code']){
//				 	$data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code'=>$value['industry_code']),['id','parent','name','name_en']);
//				 	$parent = $data[$key]['industry_info']['parent'];
//				 	$data[$key]['industry_info']['parent_name'] = $this->industry->where('code',$parent)->value('name');
//			}
//			if($value['keyword_code']){
//			 	$data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code'=>$value['keyword_code']),['id','name','name_en']);
//			}
//			if($value['product_id']){
//			  	  	$data[$key]['product_info']    = $this->corevalue->find($value['product_id'],['id','name','name_en','thumb_path']);
//			}
//		 }
//
//		$this->res['data'] = $this->response->paginator($data, new CorevalueIndexTransformer());
//		return $this->response->array($this->res)->setStatusCode($this->res['status_code']);

    }


    public function getIndustryProductList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');
        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }
        $data = $this->corevalueindex->where('product_id', '!=', 0)
            ->whereIn('industry_code', $industry_list)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        foreach ($data as $key => $value) {
            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code' => $value['industry_code']), ['id', 'parent', 'name', 'name_en']);
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }
            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en']);
            }
            if ($value['product_id']) {
                $data[$key]['product_info'] = $this->corevalue->find($value['product_id'], ['id', 'name', 'name_en', 'thumb_path']);
            }
        }

        $this->res['data'] = $this->response->paginator($data, new CorevalueIndexTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);

    }

    /**
     * @api {get} /gettechlist 获取科技首页
     * @apiDescription 获取科技首页
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {Int}   page      页数
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/gettechlist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     * {
     * "status_code": 200,
     * "message": null,
     * "data": {
     * "exception": null,
     * "headers": {},
     * "original": {
     * "current_page": 1,
     * "data": [
     * {
     * "name": "WattUp无线充电技术",
     * "name_en": "WattUp",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500096011.jpg!ptpe.img",
     * "keyword_code": "v88HFGyj2Tz7z7vO",
     * "industry_code": "IND0053",
     * "keyword_name": "Energous",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "纳米黄金笼子污水处理技术",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500089549.jpg!ptpe.img",
     * "keyword_code": "S3Jrby2WDKIhiKtQ",
     * "industry_code": "IND0048",
     * "keyword_name": "NanoSpun",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "3D打印技术",
     * "name_en": "Clip Technology",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500036112.jpg!ptpe.img",
     * "keyword_code": "8sSeNaK0mxkd1fVB",
     * "industry_code": "IND0028",
     * "keyword_name": "Carbon3D",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "TruePrint 指纹识别技术",
     * "name_en": "TruePrint",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500288313.jpg!ptpe.img",
     * "keyword_code": "kuCREhyd6IkS6n9w",
     * "industry_code": "IND0049",
     * "keyword_name": "奥森科技",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "代码压缩库 固件存储解决方案",
     * "name_en": "Code Compression Library",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500289236.jpg!ptpe.img",
     * "keyword_code": "Arn43Af7wfZ7X5BZ",
     * "industry_code": "IND0088",
     * "keyword_name": "Algotrim",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "现代计算摄影 图像存储解决方案",
     * "name_en": "Modern Computational Photography",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500289210.jpg!ptpe.img",
     * "keyword_code": "Arn43Af7wfZ7X5BZ",
     * "industry_code": "IND0088",
     * "keyword_name": "Algotrim",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "3D打印军用卫星部件",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500271690.jpg!ptpe.img",
     * "keyword_code": "cDvAIRWlbk6wfqr6",
     * "industry_code": "IND0065",
     * "keyword_name": "洛克希德·马丁",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "车辆紧急避让",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1500445774.jpg!ptpe.img",
     * "keyword_code": "HpQZoAZGlfX7Crcg",
     * "industry_code": "IND0044",
     * "keyword_name": "Waymo",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "应力线增材制造",
     * "name_en": "Stress Lines Additive Manufacturing",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1501566886.jpg!ptpe.img",
     * "keyword_code": "1eaXBR9trZFzu3zw",
     * "industry_code": "IND0028",
     * "keyword_name": "麻省理工学院建筑与规划学院",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "Smart Cover",
     * "name_en": "Smart Cover",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1502447072.jpg!ptpe.img",
     * "keyword_code": "5GLULg6uCpHAk0XT",
     * "industry_code": "IND0043",
     * "keyword_name": "苹果",
     * "industry_name": "人工智能"
     * },
     * {
     * "name": "健康监测系统",
     * "name_en": "Computer Health data",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1502447031.jpg!ptpe.img",
     * "keyword_code": "5GLULg6uCpHAk0XT",
     * "industry_code": "IND0043",
     * "keyword_name": "苹果",
     * "industry_name": "人工智能"
     * },
     * {
     * "name": "纳米铝材料",
     * "name_en": "Nano-galvanic Aluminum-based Powder",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1502933396.jpg!ptpe.img",
     * "keyword_code": "NR1fq0y9KjD2reFm",
     * "industry_code": "IND0048",
     * "keyword_name": "美军陆军研究实验室",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "均质压燃技术",
     * "name_en": "HCCI",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1503396719.jpg!ptpe.img",
     * "keyword_code": "UlgDIpvwaCDH1a4Z",
     * "industry_code": "IND0105",
     * "keyword_name": "马自达",
     * "industry_name": "Spotlight"
     * },
     * {
     * "name": "SmartComp 诊断技术",
     * "name_en": "",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1503545126.jpg!ptpe.img",
     * "keyword_code": "KGjuSbZhrIagsRtZ",
     * "industry_code": "IND0041",
     * "keyword_name": "Yogitech",
     * "industry_name": "人工智能"
     * },
     * {
     * "name": "可加热导电面料",
     * "name_en": "Heatable Electric Fabric",
     * "thumb_path": "http://uploads2.b0.upaiyun.com///tech/tech1503891099.jpg!ptpe.img",
     * "keyword_code": "j4gC0BsLw6mzyuvG",
     * "industry_code": "IND0048",
     * "keyword_name": "内蒂克士兵研发工程中心",
     * "industry_name": "Spotlight"
     * }
     * ],
     * "from": 1,
     * "last_page": 2,
     * "next_page_url": "http://xinchengapp.com/api/gettechlist?page=2",
     * "path": "http://xinchengapp.com/api/gettechlist",
     * "per_page": 15,
     * "prev_page_url": null,
     * "to": 15,
     * "total": 27
     * }
     * }
     * }
     */
    public function getTechList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'industry_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $industry = $this->industry->where('code', $request->input('industry_code'))->first();
        if (!empty($industry)) {
            if ($industry->parent == '') {
//                return $this->corevalueindex->where('industry_code',$industry->code)->pluck('product_id');
                $code = $this->industry->where('parent', $industry->code)->pluck('code');
                $tech_id = $this->corevalueindex->whereIn('industry_code', $code)->pluck('tech_id');
                $corevalue = $this->corevalue->whereIn('xc_core_value.id', $tech_id)->select('xc_core_value.id', 'xc_core_value.name', 'xc_core_value.order', 'xc_core_value.name_en', 'xc_core_value.thumb_path', 'xc_core_value.keyword_code', 'xc_core_value.industry_code', 'sys_keyword.name as keyword_name')
                    ->leftjoin('sys_keyword', 'xc_core_value.keyword_code', '=', 'sys_keyword.code')
                    ->orderBy('xc_core_value.id', 'DESC')
                    ->paginate();
                foreach ($corevalue as &$item) {
                    $corevalue_industry = $this->industry->where('code', $item->industry_code)->first();
                    if ($corevalue_industry->parent == '') {
                        $item->industry_name = $corevalue_industry->name;
                    } else {
                        $item->industry_name = $this->industry->where('code', $corevalue_industry->parent)->first()->name;
                    }
                }
                $this->res['data'] = $this->response->paginator($corevalue, new CorevalueIndexTransformer());
                return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
            }
        }
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
//        $limit = $req->get('limit', 6);
//        $industry_code_list = explode(",", $req->get('industry_code_list'));
//        if (!is_array($industry_code_list)) {
//            $this->res['status_code'] = 201;
//            $this->res['message'] = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
//            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
//        }
//        $data = $this->corevalueindex->where('tech_id', '!=', 0)
//            ->whereIn('industry_code', $industry_code_list)
//            ->orderBy('created_at', 'desc')
//            ->paginate($limit);
//        foreach ($data as $key => $value) {
//            if ($value['industry_code']) {
//                $data[$key]['industry_info'] = $this->industry->select(['id', 'parent', 'name', 'name_en'])->where(['code' => $value['industry_code']])->first();
//                $parent = $data[$key]['industry_info']['parent'];
//                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
//            }
//            if ($value['keyword_code']) {
//                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en']);
//            }
//            if ($value['tech_id']) {
//                $data[$key]['tech_info'] = $this->corevalue->find($value['tech_id'], ['id', 'name', 'name_en', 'thumb_path']);
//            }
//        }
//
//        $this->res['data'] = $this->response->paginator($data, new CorevalueIndexTransformer());
//        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    public function getIndustryTechList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');
        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }
        $data = $this->corevalueindex->where('tech_id', '!=', 0)
            ->whereIn('industry_code', $industry_list)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        foreach ($data as $key => $value) {
            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->select(['id', 'parent', 'name', 'name_en'])->where(['code' => $value['industry_code']])->first();
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }
            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en']);
            }
            if ($value['tech_id']) {
                $data[$key]['tech_info'] = $this->corevalue->find($value['tech_id'], ['id', 'name', 'name_en', 'thumb_path']);
            }
        }

        $this->res['data'] = $this->response->paginator($data, new CorevalueIndexTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getprojectlist 获取项目首页
     * @apiDescription 获取项目首页
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getprojectlist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "total": 1,
     *            "per_page": 6,
     *            "current_page": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "prev_page_url": null,
     *            "from": 1,
     *            "to": 1,
     *            "data": [
     *                {
     *                    "id": 43,
     *                    "project_id": 1,
     *                    "product_id": 0,
     *                    "tech_id": 0,
     *                    "event_id": 0,
     *                    "keyword_code": "KW0000901",
     *                    "industry_code": "IND0073",
     *                    "order": 0,
     *                    "created_at": null,
     *                    "industry_info": {
     *                        "id": 8,
     *                        "parent": "",
     *                        "name": "测试",
     *                        "name_en": "test"
     *                    },
     *                    "keyword_info": {
     *                        "id": 1,
     *                        "name": "测试项目的关键词",
     *                        "name_en": "3213"
     *                    }
     *                }
     *            ]
     *        }
     *    }
     *}
     */
    public function getProjectList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_code_list = explode(",", $req->get('industry_code_list'));
        if (!is_array($industry_code_list)) {
            $this->res['status_code'] = 201;
            $this->res['message'] = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }
        $data = $this->corevalueindex->where('project_id', '!=', 0)
            ->whereIn('industry_code', $industry_code_list)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code' => $value['industry_code']), ['id', 'parent', 'name', 'name_en']);
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }
            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en']);
            }
            if ($value['product_id']) {
                $data[$key]['product_info'] = $this->corevalue->find($value['product_id'], ['id', 'name', 'name_en', 'thumb_path']);
            }
        }

        $this->res['data'] = $this->response->paginator($data, new CorevalueIndexTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    public function getIndustryProjectList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');
        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }
        $data = $this->corevalueindex->where('project_id', '!=', 0)
            ->whereIn('industry_code', $industry_list)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['industry_code']) {
                $data[$key]['industry_info'] = $this->industry->getIndustryOne(array('code' => $value['industry_code']), ['id', 'parent', 'name', 'name_en']);
                $parent = $data[$key]['industry_info']['parent'];
                $data[$key]['industry_info']['parent_name'] = $this->industry->where('code', $parent)->value('name');
            }
            if ($value['keyword_code']) {
                $data[$key]['keyword_info'] = $this->keywords->getKeywordOne(array('code' => $value['keyword_code']), ['id', 'name', 'name_en']);
            }
            if ($value['product_id']) {
                $data[$key]['product_info'] = $this->corevalue->find($value['product_id'], ['id', 'name', 'name_en', 'thumb_path']);
            }
        }

        $this->res['data'] = $this->response->paginator($data, new CorevalueIndexTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }
//


    /**
     * @api {get} /getsetuplist 获取机构首页
     * @apiDescription 获取机构首页
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getsetuplist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "total": 1,
     *            "per_page": 6,
     *            "current_page": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "prev_page_url": null,
     *            "from": 1,
     *            "to": 1,
     *            "data": [
     *                {
     *                    "id": 43,
     *                    "project_id": 1,
     *                    "product_id": 0,
     *                    "tech_id": 0,
     *                    "event_id": 0,
     *                    "keyword_code": "KW0000901",
     *                    "industry_code": "IND0073",
     *                    "order": 0,
     *                    "created_at": null,
     *                    "industry_info": {
     *                        "id": 8,
     *                        "parent": "",
     *                        "name": "测试",
     *                        "name_en": "test"
     *                    },
     *                    "keyword_info": {
     *                        "id": 1,
     *                        "name": "测试项目的关键词",
     *                        "name_en": "3213"
     *                    }
     *                }
     *            ]
     *        }
     *    }
     *}
     */
    public function getSetupList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_code_list = explode(",", $req->get('industry_code_list'));
        if (!is_array($industry_code_list)) {
            $this->res['status_code'] = 201;
            $this->res['message'] = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }
        $data = $this->keywordindustryref->where('is_index', 1)
            ->whereIn('industry_code', $industry_code_list)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['keyword_code']) {
                $data[$key]['setup_info'] = $this->keywords->select(['id', 'oneword', 'logo_path', 'head_path', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'name', 'name_en', 'keyword_category_code'])->where(['code' => $value['keyword_code'], 'keyword_category_code' => 'KC004'])->first();
                if ($celeber = $data[$key]['setup_info']) {
                    $data[$key]['setup_info']['geo_code_country'] = $this->xcgeo->where('code', $celeber['geo_code_country'])->value('name');
                    $data[$key]['setup_info']['geo_code_province'] = $this->xcgeo->where('code', $celeber['geo_code_province'])->value('name');
                    $data[$key]['setup_info']['geo_code_city'] = $this->xcgeo->where('code', $celeber['geo_code_city'])->value('name');
                }
            }
        }
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    public function getIndustrySetupList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');
        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }
        $data = $this->keywordindustryref->where('is_index', 1)
            ->whereIn('industry_code', $industry_list)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['keyword_code']) {
                $data[$key]['setup_info'] = $this->keywords->select(['id', 'oneword', 'logo_path', 'head_path', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'name', 'name_en', 'keyword_category_code'])->where(['code' => $value['keyword_code'], 'keyword_category_code' => 'KC004'])->first();
                if ($celeber = $data[$key]['setup_info']) {
                    $data[$key]['setup_info']['geo_code_country'] = $this->xcgeo->where('code', $celeber['geo_code_country'])->value('name');
                    $data[$key]['setup_info']['geo_code_province'] = $this->xcgeo->where('code', $celeber['geo_code_province'])->value('name');
                    $data[$key]['setup_info']['geo_code_city'] = $this->xcgeo->where('code', $celeber['geo_code_city'])->value('name');
                }
            }
        }
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getcomlist 获取公司首页
     * @apiDescription 获取公司首页
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getcomlist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "total": 1,
     *            "per_page": 6,
     *            "current_page": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "prev_page_url": null,
     *            "from": 1,
     *            "to": 1,
     *            "data": [
     *                {
     *                    "id": 43, //公司[关键词]id
     *                    "code": "KW0000901", //[关键词]编码
     *                    "name": "谷歌", //中文名称
     *                    "name_en": "google", //英文名称
     *                    "logo_path": "www.xincheng.tv/aoxcy8.jpg", //缩略图路径，单笔
     *                    "oneword": "科技企业top1", //一句话描述
     *                    "order": 0, //排序序号
     *                    "country_name": "中国", //国家名称
     *                    "city_name": "北京" //城市名称
     *                }
     *            ]
     *        }
     *    }
     *}
     */

    public function getComList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'industry_code' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $industry = $this->industry->where('code', $request->input('industry_code'))->first();
        if (!empty($industry)) {
            if ($industry->parent == '') {
//                return $this->corevalueindex->where('industry_code',$industry->code)->pluck('product_id');
                $code = $this->industry->where('parent', $industry->code)->pluck('code');
                $company_keyword_code = $this->corevalueindex->whereIn('industry_code', $code)->pluck('keyword_code');
                $keywords= $this->keywords->whereIn('sys_keyword.code', $company_keyword_code)
                    ->where('sys_keyword.keyword_category_code', 'KC002')
                    ->select('sys_keyword.id', 'sys_keyword.code', 'sys_keyword.name', 'sys_keyword.name_en', 'sys_keyword.logo_path', 'sys_keyword.oneword', 'sys_keyword.order', 'sys_geo.name as country_name', 'city.name as city_name')
                    ->leftjoin('sys_geo', 'sys_keyword.geo_code_country', '=', 'sys_geo.code')
                    ->leftjoin('sys_geo as city', 'sys_keyword.geo_code_province', 'city.code')
                    ->orderBy('sys_keyword.id', 'DESC')
                    ->paginate();
                $this->res['data'] = $this->response->paginator($keywords, new KeywordTransformer);
                return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
            }
        }
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
//        $limit = $req->get('limit', 6);
//        $industry_code_list = explode(",", $req->get('industry_code_list'));
//        if (!is_array($industry_code_list)) {
//            $this->res['status_code'] = 201;
//            $this->res['message'] = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
//            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
//        }
//        $data = $this->keywordindustryref->where('is_index', 1)
//            ->whereIn('industry_code', $industry_code_list)
//            ->orderBy('order', 'asc')
//            ->orderBy('created_at', 'desc')
//            ->paginate($limit);
//
//        foreach ($data as $key => $value) {
//            if ($value['keyword_code']) {
//                $data[$key]['com_info'] = $this->keywords->select(['id', 'oneword', 'logo_path', 'head_path', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'name', 'name_en', 'keyword_category_code'])->where(['code' => $value['keyword_code'], 'keyword_category_code' => 'KC002'])->first();
//                if ($celeber = $data[$key]['com_info']) {
//                    $data[$key]['com_info']['geo_code_country'] = $this->xcgeo->where('code', $celeber['geo_code_country'])->value('name');
//                    $data[$key]['com_info']['geo_code_province'] = $this->xcgeo->where('code', $celeber['geo_code_province'])->value('name');
//                    $data[$key]['com_info']['geo_code_city'] = $this->xcgeo->where('code', $celeber['geo_code_city'])->value('name');
//                }
//            }
//        }
//        $this->res['data'] = $data;
//        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    public function getIndustryComList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');
        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }
        $data = $this->keywordindustryref->where('is_index', 1)
            ->whereIn('industry_code', $industry_list)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['keyword_code']) {
                $data[$key]['com_info'] = $this->keywords->select(['id', 'oneword', 'logo_path', 'head_path', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'name', 'name_en', 'keyword_category_code'])->where(['code' => $value['keyword_code'], 'keyword_category_code' => 'KC002'])->first();
                if ($celeber = $data[$key]['com_info']) {
                    $data[$key]['com_info']['geo_code_country'] = $this->xcgeo->where('code', $celeber['geo_code_country'])->value('name');
                    $data[$key]['com_info']['geo_code_province'] = $this->xcgeo->where('code', $celeber['geo_code_province'])->value('name');
                    $data[$key]['com_info']['geo_code_city'] = $this->xcgeo->where('code', $celeber['geo_code_city'])->value('name');
                }
            }
        }
        $this->corevalueindex->where('industry_code', $value['keyword_code'])->get();
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getcelebritylist 获取人物首页
     * @apiDescription 获取人物首页
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {Int}   limit     单页条数
     * @apiParam {string} industry_code_list   行业Code 可传多个以英文逗号隔开  值的格式应   xxx,xxx,xxx,xxx
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getcelebritylist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "total": 2,
     *        "per_page": 6,
     *        "current_page": 1,
     *        "last_page": 1,
     *        "next_page_url": null,
     *        "prev_page_url": null,
     *        "from": 1,
     *        "to": 2,
     *        "data": [
     *            {
     *                "id": 49,
     *                "keyword_code": "E6AmWPjZWnxYPFml",
     *                "industry_code": "IND0045",
     *                "is_index": 1,
     *                "order": 49,
     *                "created_at": "2017-09-12 08:59:52",
     *                "celebrity_info": {
     *                    "id": 7,
     *                    "oneword": "Alphabet董事长",
     *                    "logo_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-qzC82LdTJn.jpeg",
     *                    "head_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-Z1quZeZAw5.jpeg",
     *                    "geo_code_country": "美国",
     *                    "geo_code_province": "加利福尼亚",
     *                    "geo_code_city": null,
     *                    "name": "埃里克·施密特",
     *                    "name_en": "Eric Schmidt",
     *                    "keyword_category_code": "KC003"
     *                }
     *            },
     *            {
     *                "id": 180,
     *                "keyword_code": "TzwgcZhjQtZAE59V",
     *                "industry_code": "IND0045",
     *                "is_index": 1,
     *                "order": 180,
     *                "created_at": "2017-09-13 10:14:30",
     *                "celebrity_info": null
     *            }
     *        ]
     *    }
     *}
     */
    public function getCelebrityList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_code_list = explode(",", $req->get('industry_code_list'));

        if (!is_array($industry_code_list)) {
            $this->res['status_code'] = 201;
            $this->res['message'] = 'industry_code_list 值的格式应   xxx,xxx,xxx,xxx';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }

        $data = $this->keywordindustryref->where('is_index', 1)
            ->whereIn('industry_code', $industry_code_list)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['keyword_code']) {
                $data[$key]['celebrity_info'] = $this->keywords->select(['id', 'code', 'oneword', 'logo_path', 'head_path', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'name', 'name_en', 'keyword_category_code'])->where(['code' => $value['keyword_code'], 'keyword_category_code' => 'KC003'])->first();
                if ($celeber = $data[$key]['celebrity_info']) {
                    $data[$key]['celebrity_info']['geo_code_country'] = $this->xcgeo->where('code', $celeber['geo_code_country'])->value('name');
                    $data[$key]['celebrity_info']['geo_code_province'] = $this->xcgeo->where('code', $celeber['geo_code_province'])->value('name');
                    $data[$key]['celebrity_info']['geo_code_city'] = $this->xcgeo->where('code', $celeber['geo_code_city'])->value('name');
                    $type_code_role = $this->management->where('executive', $value['keyword_code'])->value('type_code_role');
                    $data[$key]['celebrity_info']['management_role'] = $this->type->where('pcode', $type_code_role)->value('name');
                }
            }
        }

        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    public function getIndustryCelebrityList(Request $req)
    {
        $limit = $req->get('limit', 6);
        $industry_parent_code = $req->get('industry_parent_code');
        $industry_code_list = $this->industry->where('parent', $industry_parent_code)->get(['code']);
        $industry_list = null;
        foreach ($industry_code_list as $key => $value) {
            $industry_list[$key] = $value['code'];
        }

        $data = $this->keywordindustryref->where('is_index', 1)
            ->whereIn('industry_code', $industry_list)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            if ($value['keyword_code']) {
                $data[$key]['celebrity_info'] = $this->keywords->select(['id', 'oneword', 'logo_path', 'head_path', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'name', 'name_en', 'keyword_category_code'])->where(['code' => $value['keyword_code'], 'keyword_category_code' => 'KC003'])->first();
                if ($celeber = $data[$key]['celebrity_info']) {
                    $data[$key]['celebrity_info']['geo_code_country'] = $this->xcgeo->where('code', $celeber['geo_code_country'])->value('name');
                    $data[$key]['celebrity_info']['geo_code_province'] = $this->xcgeo->where('code', $celeber['geo_code_province'])->value('name');
                    $data[$key]['celebrity_info']['geo_code_city'] = $this->xcgeo->where('code', $celeber['geo_code_city'])->value('name');
                    $type_code_role = $this->management->where('executive', $value['keyword_code'])->value('type_code_role');
                    $data[$key]['celebrity_info']['management_role'] = $this->type->where('pcode', $type_code_role)->value('name');
                }
            }
        }

        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getindustry 获取PC左边导航所有
     * @apiDescription 获取PC左边导航所有
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getindustry
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.cp+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": [
     *        {
     *            "code": "IND0001",
     *            "name": "人工智能",
     *            "name_en": "Artificial Intelligence",
     *            "subset": [
     *                {
     *                    "code": "IND0008",
     *                    "name": "认知计算",
     *                    "name_en": " Cognitive Computing"
     *                },
     *                {
     *                    "code": "IND0009",
     *                    "name": "感知计算",
     *                    "name_en": "Aware Computing"
     *                },
     *                {
     *                    "code": "IND0010",
     *                    "name": "机器视觉",
     *                    "name_en": "Machine Vision"
     *                },
     *                {
     *                    "code": "IND0011",
     *                    "name": "计算机视觉",
     *                    "name_en": "Computer Vision"
     *                },
     *                {
     *                    "code": "IND0012",
     *                    "name": "自动驾驶技术",
     *                    "name_en": "Autopilot Technology"
     *                },
     *                {
     *                    "code": "IND0013",
     *                    "name": "机器人技术",
     *                    "name_en": "Robotics"
     *                },
     *                {
     *                    "code": "IND0014",
     *                    "name": "量子计算",
     *                    "name_en": "Quantum Computing"
     *                },
     *                {
     *                    "code": "IND0015",
     *                    "name": "神经元芯片",
     *                    "name_en": "Neuron Chip"
     *                },
     *                {
     *                    "code": "IND0016",
     *                    "name": "图像识别",
     *                    "name_en": " Image Identification"
     *                },
     *                {
     *                    "code": "IND0017",
     *                    "name": "人脸识别",
     *                    "name_en": "Face Recognition"
     *                },
     *                {
     *                    "code": "IND0018",
     *                    "name": "眼球识别",
     *                    "name_en": "Eye Recognition"
     *                },
     *                {
     *                    "code": "IND0019",
     *                    "name": "手势识别",
     *                    "name_en": "Gesture Recognition"
     *                },
     *                {
     *                    "code": "IND0020",
     *                    "name": "语义理解",
     *                    "name_en": " Semantic Understanding"
     *                },
     *                {
     *                    "code": "IND0021",
     *                    "name": "语音识别",
     *                    "name_en": " Speech Recognition"
     *                }
     *            ]
     *        },
     *        {
     *            "code": "IND0002",
     *            "name": "虚拟/增强现实",
     *            "name_en": "Virtual / Augmented Reality",
     *            "subset": [
     *                {
     *                    "code": "IND0022",
     *                    "name": "虚拟现实技术",
     *                    "name_en": "Virtual Reality Technology"
     *                },
     *                {
     *                    "code": "IND0023",
     *                    "name": "虚拟现实设备",
     *                    "name_en": "Virtual Reality Devices"
     *                },
     *                {
     *                    "code": "IND0024",
     *                    "name": "增强现实技术",
     *                    "name_en": " Augmented Reality"
     *                },
     *                {
     *                    "code": "IND0025",
     *                    "name": "增强显示设备",
     *                    "name_en": "Augmented Reality Device"
     *                },
     *                {
     *                    "code": "IND0026",
     *                    "name": "交互投影技术",
     *                    "name_en": " Interactive Projection"
     *                }
     *            ]
     *        },
     *        {
     *            "code": "IND0003",
     *            "name": "前沿科技",
     *            "name_en": "Spotlight",
     *            "subset": [
     *                {
     *                    "code": "IND0027",
     *                    "name": "无人机",
     *                    "name_en": " Consumer Drones"
     *                },
     *                {
     *                    "code": "IND0028",
     *                    "name": "超级高铁",
     *                    "name_en": "Super High-Speed Rail"
     *                },
     *                {
     *                    "code": "IND0029",
     *                    "name": "未来交通工具",
     *                    "name_en": " Future Vehicles"
     *                },
     *                {
     *                    "code": "IND0030",
     *                    "name": "3D打印技术",
     *                    "name_en": "3D Printing Manufacturing"
     *                },
     *                {
     *                    "code": "IND0031",
     *                    "name": "纳米科技",
     *                    "name_en": "Nano Technology"
     *                },
     *                {
     *                    "code": "IND0032",
     *                    "name": "触控技术",
     *                    "name_en": "Touch Technology"
     *                },
     *                {
     *                    "code": "IND0033",
     *                    "name": "区块链应用",
     *                    "name_en": "Block Chain Applications"
     *                },
     *                {
     *                    "code": "IND0034",
     *                    "name": "数据传输技术",
     *                    "name_en": "Data Transmission Technology"
     *                },
     *                {
     *                    "code": "IND0035",
     *                    "name": "农业科技",
     *                    "name_en": " Agricultural Technology"
     *                },
     *                {
     *                    "code": "IND0036",
     *                    "name": "科技孵化器",
     *                    "name_en": "Technology Incubator"
     *                },
     *                {
     *                    "code": "IND0037",
     *                    "name": "食品科技",
     *                    "name_en": "Food Science"
     *                },
     *                {
     *                    "code": "IND0038",
     *                    "name": "智能穿戴设备",
     *                    "name_en": "Smart Wearable Devices"
     *                },
     *                {
     *                    "code": "IND0039",
     *                    "name": "智能家居产品",
     *                    "name_en": " Smart Home Products"
     *                },
     *                {
     *                    "code": "IND0040",
     *                    "name": "体感外设",
     *                    "name_en": "Somatosensory peripherals"
     *                },
     *                {
     *                    "code": "IND0041",
     *                    "name": "无线充电技术",
     *                    "name_en": "Wireless Charging Technology"
     *                },
     *                {
     *                    "code": "IND0042",
     *                    "name": "眼球追踪技术",
     *                    "name_en": " Eye-Tracking Technology"
     *                }
     *            ]
     *        },
     *        {
     *            "code": "IND0004",
     *            "name": "互联网科技",
     *            "name_en": " Internet Technology",
     *            "subset": [
     *                {
     *                    "code": "IND0043",
     *                    "name": "云计算",
     *                    "name_en": " Cloud Computing"
     *                },
     *                {
     *                    "code": "IND0044",
     *                    "name": "电商技术",
     *                    "name_en": "E-Commerce Technology"
     *                },
     *                {
     *                    "code": "IND0045",
     *                    "name": "搜索引擎",
     *                    "name_en": "Search Engine"
     *                },
     *                {
     *                    "code": "IND0046",
     *                    "name": "流媒体技术",
     *                    "name_en": "Streaming Media Technology"
     *                },
     *                {
     *                    "code": "IND0047",
     *                    "name": "共享经济",
     *                    "name_en": "Sharing Economy"
     *                },
     *                {
     *                    "code": "IND0048",
     *                    "name": "社交媒体",
     *                    "name_en": "Social Media"
     *                },
     *                {
     *                    "code": "IND0049",
     *                    "name": "即时通讯",
     *                    "name_en": "Instant Messaging"
     *                },
     *                {
     *                    "code": "IND0050",
     *                    "name": "在线支付",
     *                    "name_en": " Online Payment"
     *                },
     *                {
     *                    "code": "IND0051",
     *                    "name": "协同办公",
     *                    "name_en": " Collaborative Productivity"
     *                },
     *                {
     *                    "code": "IND0052",
     *                    "name": "日程应用",
     *                    "name_en": " Agenda Application"
     *                },
     *                {
     *                    "code": "IND0053",
     *                    "name": "地图应用",
     *                    "name_en": "Map Application"
     *                },
     *                {
     *                    "code": "IND0054",
     *                    "name": "云安全",
     *                    "name_en": "Cloud Security"
     *                },
     *                {
     *                    "code": "IND0055",
     *                    "name": "云服务",
     *                    "name_en": "Cloud Service"
     *                },
     *                {
     *                    "code": "IND0056",
     *                    "name": "多媒体处理技术",
     *                    "name_en": "Multimedia Processing"
     *                },
     *                {
     *                    "code": "IND0057",
     *                    "name": "物联网技术",
     *                    "name_en": "Internet of Things"
     *                }
     *            ]
     *        },
     *        {
     *            "code": "IND0005",
     *            "name": "太空探索",
     *            "name_en": "Space Exploration",
     *            "subset": [
     *                {
     *                    "code": "IND0058",
     *                    "name": "火箭发射技术",
     *                    "name_en": " Rocket"
     *                },
     *                {
     *                    "code": "IND0059",
     *                    "name": "空间站",
     *                    "name_en": " Space Station"
     *                },
     *                {
     *                    "code": "IND0060",
     *                    "name": "空间探测器",
     *                    "name_en": "Space Probe"
     *                },
     *                {
     *                    "code": "IND0061",
     *                    "name": "卫星科技",
     *                    "name_en": "Satellite"
     *                },
     *                {
     *                    "code": "IND0062",
     *                    "name": "卫星应用技术",
     *                    "name_en": "Satellite Application"
     *                },
     *                {
     *                    "code": "IND0063",
     *                    "name": "空天飞机",
     *                    "name_en": "Space Plane"
     *                }
     *            ]
     *        },
     *        {
     *            "code": "IND0006",
     *            "name": "生物科技",
     *            "name_en": "Biological Technology",
     *            "subset": [
     *                {
     *                    "code": "IND0064",
     *                    "name": "3D生物打印",
     *                    "name_en": "3D Bioprinting"
     *                },
     *                {
     *                    "code": "IND0065",
     *                    "name": "仿生科技",
     *                    "name_en": "Bionic Technology"
     *                },
     *                {
     *                    "code": "IND0066",
     *                    "name": "基因工程",
     *                    "name_en": "Genetic Engineering"
     *                },
     *                {
     *                    "code": "IND0067",
     *                    "name": "疾病筛查技术",
     *                    "name_en": "Disease Screening"
     *                },
     *                {
     *                    "code": "IND0068",
     *                    "name": "免疫技术",
     *                    "name_en": "Cognitive Computing"
     *                },
     *                {
     *                    "code": "IND0069",
     *                    "name": "微生物组学",
     *                    "name_en": "Microbiome Omics"
     *                },
     *                {
     *                    "code": "IND0070",
     *                    "name": "神经科学",
     *                    "name_en": "Neuroscience"
     *                }
     *            ]
     *        },
     *        {
     *            "code": "IND0007",
     *            "name": "能源科技",
     *            "name_en": "Energy Technology",
     *            "subset": [
     *                {
     *                    "code": "IND0071",
     *                    "name": "核能",
     *                    "name_en": "Nuclear Energy"
     *                },
     *                {
     *                    "code": "IND0072",
     *                    "name": "储能技术",
     *                    "name_en": "Energy Storage"
     *                },
     *                {
     *                    "code": "IND0073",
     *                    "name": "太阳能",
     *                    "name_en": "Solar Technology"
     *                },
     *                {
     *                    "code": "IND0074",
     *                    "name": "清洁能源",
     *                    "name_en": "Clean Energy"
     *                },
     *                {
     *                    "code": "IND0075",
     *                    "name": "石墨烯",
     *                    "name_en": "Graphene"
     *                },
     *                {
     *                    "code": "IND0076",
     *                    "name": "新型燃料",
     *                    "name_en": "Fuel Cell"
     *                },
     *                {
     *                    "code": "IND0077",
     *                    "name": "引擎技术",
     *                    "name_en": "Engine Technology"
     *                }
     *            ]
     *        }
     *    ]
     *}
     *
     */
    public function getIndustry(Request $req)
    {

        $industry_list = $this->industry->where('parent', '')->get(['code', 'name', 'name_en']);

        foreach ($industry_list as $key => $value) {
            $industry_list[$key]['subset'] = $this->industry->where('parent', $value['code'])->get(['code', 'name', 'name_en']);
        }

        $this->res['data'] = $industry_list;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);

    }


    /**
     * @api {get} /getoenindustry 获取左边导航一级
     * @apiDescription 获取左边导航一级
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getoenindustry
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     * "status_code": 200,
     * "message": null,
     * "data": [
     * {
     * "code": "IND0001",
     * "name": "金融",
     * "name_en": "financial"
     * },
     * {
     * "code": "IND0073",
     * "name": "测试",
     * "name_en": "test"
     * },
     * {
     * "code": "IND0009",
     * "name": "视频图片",
     * "name_en": "shipin"
     * },
     * {
     * "code": "IND0010",
     * "name": "test111",
     * "name_en": "11111"
     * },
     * {
     * "code": "IND0017",
     * "name": "女给你那边",
     * "name_en": "CVBSVB"
     * },
     * {
     * "code": "IND0019",
     * "name": "奥富瓦",
     * "name_en": "sef"
     * },
     * {
     * "code": "IND0020",
     * "name": "娃儿发为22",
     * "name_en": "23423d"
     * }
     * ]
     * }
     */

    public function getOenIndustry(Request $req)
    {
        //$this->industry
        $this->res['data'] = $this->industry->where('parent', '')->get(['code', 'name', 'name_en']);
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /gettwoindustry 获取左边导航二级
     * @apiDescription 获取左边导航二级
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiParam {string} industry_code  一级行业Code
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/gettwoindustry
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     * "status_code": 200,
     * "message": null,
     * "data": [
     * {
     * "code": "IND0011",
     * "name": "交通",
     * "name_en": "awfawfw"
     * },
     * {
     * "code": "IND0015",
     * "name": "阿尔哇发文",
     * "name_en": "wafwe"
     * },
     * {
     * "code": "IND0016",
     * "name": "安慰法",
     * "name_en": "awfeawe"
     * },
     * {
     * "code": "IND0059",
     * "name": "行业类型1",
     * "name_en": ""
     * }
     * ]
     * }
     */
    public function getTwoIndustry(Request $req)
    {
        $validator = \Validator::make($req->all(), [
            'industry_code' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $attributes = $req->all();
        $industry_code = $attributes['industry_code'];
        $this->res['data'] = $this->industry->where('parent', $industry_code)->get(['code', 'name', 'name_en']);
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /getcarousel 获取PC首页轮播
     * @apiDescription 获取PC首页轮播
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getcarousel
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.pc+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": [
     *        {
     *            "id": 2,
     *            "type": 2,
     *            "title": "这是个视频的测试轮播",
     *            "subtitle": "我是个副标题",
     *            "intro": "我简介是一个富文本类型 <br/>wo huang hanle ",
     *            "photo": "http://xincheng-img.b0.upaiyun.com/xincheng-img-egPgp0k6yl.jpeg",
     *            "url": "这是个视频url",
     *            "created_at": "2017-09-15 04:57:50"
     *        },
     *        {
     *            "id": 1,
     *            "type": 1,
     *            "title": "这个是新闻的测试轮播",
     *            "subtitle": "我是副标题 ",
     *            "intro": "我简介是一个富文本类型 <br/>wo huang hanle ",
     *            "photo": "http://xincheng-img.b0.upaiyun.com/xincheng-img-olRvEdHHvq.jpeg",
     *            "url": "这是个url",
     *            "created_at": "2017-09-15 04:56:28"
     *        }
     *    ]
     *}
     */
    public function getCarousel(Request $req)
    {
        // 	$limit = $req->get('limit', 3);

        // $data = $this->content->where(['position'=>3])->where(function($query){
        // 					  		$query->orWhere('video_id','!=',0)
        // 								  ->orWhere('news_id','!=',0);
        // 					  })->orderBy('created_at', 'desc')
        // 					  	->paginate($limit);
        // foreach ($data as $key => $value) {

        // 	$data[$key]['news_info'] = null;
        // 	$data[$key]['video_info'] = null;

        // 	 if($value['news_id']){
        //   	  	$data[$key]['news_info']= $this->draft->find($value['news_id'],['id','title','subtitle','thumb_path']);
        //   	  	$data[$key]['news_info']['desc_list'] = $this->draftintro->where('draft_id',$data[$key]['news_info']->id)->orderBy('order','asc')->limit(2)->get(['id','desc']);
        //   	 }

        //   	 if($value['video_id']){
        //   		$data[$key]['video_info']   = $this->video->find($value['video_id'],['id','title','subtitle','thumb_path','url']);

        //   	 }
        // }
        //$limit = $req->get('limit', 3);

        $data = $this->recommend->where('industry_code', 0)->orderBy('order', 'asc')->orderBy('created_at', 'desc')->limit(4)->get();

        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {get} /getindexlist 获取PC首页新闻
     * @apiDescription 获取PC首页新闻
     * @apiGroup IndexController
     * @apiPermission 否
     * @apiHeader {String} Accept  application/vnd.lumen.pc+json
     * @apiParam {Int} limit  当前页个数
     * @apiParam {Int} page  当前页
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getindexlist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.pc+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     *    "status_code": 200,
     *    "message": null,
     *    "data": {
     *        "exception": null,
     *        "headers": {},
     *        "original": {
     *            "total": 1,
     *            "per_page": 6,
     *            "current_page": 1,
     *            "last_page": 1,
     *            "next_page_url": null,
     *            "prev_page_url": null,
     *            "from": 1,
     *            "to": 1,
     *            "data": [
     *                {
     *                    "id": 29,
     *                    "news_id": 182,
     *                    "video_id": 0,
     *                    "gallery_id": 0,
     *                    "keyword_code": "MO08nRmLhbAMyq8t",
     *                    "industry_code": "IND0053",
     *                    "is_main": 1,
     *                    "position": 1,
     *                    "project_id": 0,
     *                    "product_id": 0,
     *                    "tech_id": 0,
     *                    "event_id": 0,
     *                    "order": 0,
     *                    "created_at": "2017-09-13 06:18:07",
     *                    "news_info": {
     *                        "id": 182,
     *                        "title": "",
     *                        "subtitle": "",
     *                        "thumb_path": "",
     *                        "picture_list": [],
     *                        "gallery_list": {
     *                            "picture_list": null
     *                        },
     *                        "desc_list": [
     *                            {
     *                                "id": 104,
     *                                "desc": "https://codepen.io/bradwestfall/project/editor/XWNWge?preview_height=50&amp;open_file=src/app.jshttps://codepen.io/bradwestfall/project/editor/*XWNWge?preview_height=50&amp;open_file=src/app.jshttps://codepen.io/bradwestfall/project/editor/XWNWge?preview_h"
     *                            }
     *                        ]
     *                    },
     *                    "video_info": null,
     *                    "draft_edge_list": {
     *                        "keyword_info_list": null,
     *                        "porduct_info_list": [],
     *                        "technology_info_list": [],
     *                        "project_info_list": [],
     *                        "keyfigures_info_list": [],
     *                        "m_subcom_info_list": null,
     *                        "subcom_info_list": null,
     *                        "investor_info_list": null
     *                    }
     *                }
     *            ]
     *        }
     *    }
     *}
     */
    public function getIndexList(Request $req)
    {
        $limit = $req->get('limit', 6);

        $data = $this->content->where(['position' => 1])->orWhere('position', '3')->where(function ($query) {
            //$query->orWhere('video_id','!=',0)
            $query->orWhere('news_id', '!=', 0);
        })->groupBy('news_id')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        foreach ($data as $key => $value) {
            $data[$key]['news_info'] = null;
            $data[$key]['video_info'] = null;
            if ($value['news_id']) {
                $rs_draft = $this->draft->find($value['news_id'], ['id', 'title', 'subtitle', 'thumb_path']);
                if (empty($rs_draft)) continue;

                $data[$key]['news_info'] = $rs_draft;
                $picture_list = $this->draftbody->where(['draft_id' => $data[$key]['news_info']->id, 'element_type' => 'picture'])
                    ->orderBy('element_order', 'asc')->limit(3)->get(['element_id', 'element_type', 'element_content']);
                $picture = array();
                if (!empty($picture_list)) {
                    $sun = count($picture_list);
                    foreach ($picture_list as $k => $v) {
                        $tmp = $this->picture->where('id', $v['element_id'])->first(['id', 'thumb_path', 'original_path', 'desc']);

                        if ($sun == 1) {
                            $tmp['thumb_path'] = $tmp['original_path'] . '!draft800';
                        }
                        if ($sun == 2) {
                            $tmp['thumb_path'] = $tmp['original_path'] . '!draft650';
                        }
                        if ($sun >= 3) {
                            $tmp['thumb_path'] = $tmp['original_path'] . '!draft500';
                        }

                        $picture[$k] = json_decode(json_encode($tmp), TRUE);
                    }
                }
                $data[$key]['news_info']['picture_list'] = $picture;

                $gallery_data = $this->draftbody->where(['draft_id' => $data[$key]['news_info']->id, 'element_type' => 'gallery'])
                    ->orderBy('element_order', 'asc')->first();


                if ($gallery_data) {
                    $gallery_picture_list = $this->picture->where('gallery_id', $gallery_data->element_id)->orderBy('order', 'asc')->limit(7)->get(['id', 'thumb_path', 'original_path', 'desc']);
                    foreach ($gallery_picture_list as $ke => $va) {
                        $gallery_picture_list[$ke]['thumb_path'] = $va['original_path'] . '!draft100';
                    }

                    $gallery_data['picture_list'] = $gallery_picture_list;
                } else {
                    $gallery_data['picture_list'] = null;
                }

                $data[$key]['news_info']['gallery_list'] = $gallery_data;
                $data[$key]['news_info']['desc_list'] = $this->draftbody->where('draft_id', $data[$key]['news_info']->id)->where('element_type', 'intro')->orderBy('element_order', 'asc')->limit(2)->get(['id', 'element_content']);

                $xc_keyword_code = $this->draftKeyword->where('draft_id', $value['news_id'])->first(['id', 'keyword_code']);
                $keyword_info_list = $this->keywords->where('code', $xc_keyword_code['keyword_code'])
                    ->first(['name_en', 'name', 'oneword', 'desc', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'birth', 'logo_path', 'head_path']);

                $keyword_info_list['geo_list'] = array('country' => $this->xcgeo->where('code', $keyword_info_list['geo_code_country'])->value('name'),
                    'province' => $this->xcgeo->where('code', $keyword_info_list['geo_code_province'])->value('name'),
                    'city' => $this->xcgeo->where('code', $keyword_info_list['geo_code_city'])->value('name')
                );
                $edge_list['keyword_info_list'] = !empty($keyword_info_list) ? $keyword_info_list : null;

                $edge_list['porduct_info_list'] = $this->corevalue->where('keyword_code', $xc_keyword_code['keyword_code'])
                    ->where('type', 'p')
                    ->get(['id', 'keyword_code', 'name_en', 'name'])
                    ->toArray();

                $edge_list['technology_info_list'] = $this->corevalue->where('keyword_code', $xc_keyword_code['keyword_code'])
                    ->where('type', 't')
                    ->get(['id', 'keyword_code', 'name'])
                    ->toArray();

                $edge_list['project_info_list'] = $this->corevalue->where('keyword_code', $xc_keyword_code['keyword_code'])
                    ->where('type', 't')
                    ->get(['id', 'keyword_code', 'name'])
                    ->toArray();

                $edge_list['keyfigures_info_list'] = $this->management->leftJoin('sys_keyword', 'xc_management.executive', '=', 'sys_keyword.code')
                    ->leftJoin('sys_type', 'xc_management.type_code_role', '=', 'sys_type.code')
                    ->where('keyword_code', $xc_keyword_code['keyword_code'])
                    ->get(['xc_management.id', 'xc_management.keyword_code', 'executive', 'xc_management.type_code_role', 'sys_keyword.name', 'sys_keyword.name_en', 'sys_type.name as role_name', 'sys_type.name_en as role_name_en'])
                    ->toArray();

                $edge_list['m_subcom_info_list'] = $this->subcom->where('keyword_code', $xc_keyword_code['keyword_code'])
                    ->leftJoin('sys_keyword', 'xc_subcom.keyword_code', '=', 'sys_keyword.code')
                    ->first();

                $edge_list['subcom_info_list'] = $this->subcom->where('sub_keyword_code', $xc_keyword_code['keyword_code'])
                    ->leftJoin('sys_keyword', 'xc_subcom.sub_keyword_code', '=', 'sys_keyword.code')
                    ->first();

                $edge_list['investor_info_list'] = null;
                $query = $this->financing->select('investor')
                    ->where('keyword_code', $xc_keyword_code['keyword_code'])
                    ->leftJoin('data_investor', 'data_financing.id', '=', 'data_investor.financing_id')
                    ->distinct()
                    ->get()
                    ->toArray();
                foreach ($query as $key => $value) {
                    $keyword_data = $this->keywords->where('code', $value['investor'])
                        ->first(['id', 'name', 'oneword', 'desc', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'birth', 'logo_path', 'head_path']);
                    $edge_list['investor_info_list'][$key] = !empty($keyword_data) ? $keyword_data : null;
                }

                $data[$key]['draft_edge_list'] = $edge_list;
            }
        }
        // if($value['video_id']){
        // $data[$key]['video_info']   = $this->video->find($value['video_id'],['id','title','subtitle','thumb_path','url']);

        // }
        $this->res['data'] = $this->response->paginator($data, new ContentTransformer());
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /coreinfoblock 产品科技项目信息块
     * @apiGroup Corevalue
     * @apiPermission 需要
     * @apiParam {Int}      core_id    产科项id
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "status_code": 200,
     *          "message": "获取成功",
     *          "data": {
     *             "core_id": 746,
     *             "name": "无人驾驶货运飞机FM15",
     *             "thumb_path": "http://uploads2.b0.upaiyun.com///prod/prod1505733974.jpg!ptpe.img",
     *             "keyword_name": "帆美航空",
     *             "keyword_name_en": "Fivmate",
     *             "industry_name": "无人机",
     *             "industry_name_en": "无人机",
     *             "core_record": [
     *                      {
     *                          "id": 8,
     *                          "content": "无人驾驶货运飞机FM15的原型机是运5B型运输机。",
     *                          "order": 0
     *                      },
     *                      {
     *                          "id": 10,
     *                          "content": "改造后的飞机具体性能为：起飞重量约为5.5吨、飞机载货空间为11.8立方米、飞机载重1.5吨、航程为1000公里、最小起飞距离仅有50米。",
     *                          "order": 0
     *                      }
     *                    ]
     *                  }
     *               }
     */
    public function getCoreInfoBlock(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'core_id' => 'required',
        ]);
        if ($validator->fails()) return $this->errorBadRequest($validator);
        $core_data = $this->corevalue
            ->select('xc_core_value.id as core_id', 'xc_core_value.name', 'xc_core_value.thumb_path', 'sys_keyword.name as keyword_name', 'sys_keyword.name_en as keyword_name_en', 'sys_industry.name as industry_name', 'sys_industry.name_en as industry_name_en')
            ->where('xc_core_value.id', $request->input('core_id'))
            ->leftjoin('sys_keyword', 'xc_core_value.keyword_code', '=', 'sys_keyword.code')
            ->leftjoin('sys_industry', 'xc_core_value.industry_code', '=', 'sys_industry.code')
            ->first();
        if (!empty($core_data)) {
            $core_data->core_record = $this->corerecord->select('id', 'content', 'order')->where('core_id', $core_data->core_id)->orderBy('order', 'ASC')->get();
        }
        $this->res['data'] = $core_data;
        $this->res['message'] = '获取成功';
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /keywordinfoblock 关键词信息块
     * @apiDescription 新关键词信息块
     * @apiGroup KeyWords
     * @apiPermission 需要
     * @apiParam {Int} keyword_code  关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *    {
     *          "status_code": 200,
     *          "message": "获取成功",
     *          "data": {
     *                "0": {
     *                   "keyword_block_id": 20,
     *                   "title": "创立过程",
     *                  "keywordblockinfo": {
     *                        "content": "Square Roots由Tobias Peggs和Kimbal Musk于2016年7月联合创立，Tobias Peggs担任公司的CEO，而Kimbal Musk则是著名科技企业家Elon Musk的弟弟，此前因建立The Kitchen餐厅出名。\n"
     *                  }
     *                  },
     *                "1": {
     *                    "keyword_block_id": 21,
     *                    "title": "业务方向",
     *                   "keywordblockinfo": {
     *                      "content": "Square Roots以集装箱作为生产基地，利用高科技控制植物的生长，为城市的居民提供新鲜健康的蔬菜，其种植的蔬菜具有生长周期短，耗水量低，绿色无污染，可定制等特点。"
     *                      }
     *                   },
     *                  "keyword_code": "MBM4dyPwtOSfprzE"
     *                  }
     *                  }
     */
    public function keywordInfoBlock(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'keyword_code' => 'required',
        ]);
        if ($validator->fails()) return $this->errorBadRequest($validator);
        $data = $this->key_word_block->select('id as keyword_block_id', 'title')->where('keyword_code', $request->input('keyword_code'))->orderBy('order', 'asc')->get();

        if (!empty($data)) {
            foreach ($data as &$item) {
                $item->keywordblockinfo = $this->keywordblockinfo->select('content')->orderBy('order', 'asc')->where('block_id', $item->keyword_block_id)->first();
            }
        }
        $this->res['data'] = $data;
        $this->res['message'] = '获取成功';
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

}