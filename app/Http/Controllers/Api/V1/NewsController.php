<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Gallery;
use App\Models\Video;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Draft;
use App\Models\DraftIndustry;
use App\Models\DraftKeyword;
use App\Models\DraftIntro;
use App\Models\DraftBody;
use App\Models\Industry;
use App\Models\Keywords;
use App\Models\KeyWordBlockInfo;
use App\Models\Corevalue;
use App\Models\Management;
use App\Models\Subcom;
use App\Models\Picture;
use App\Models\Geo;
use App\Models\Financing;
use App\Utils\InsideConnect;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;

class NewsController extends BaseController
{

    public function __construct(KeyWordBlockInfo $keywordblockinfo, Geo $xcgeo, Picture $picture, User $user, Financing $financing, InsideConnect $insideconnect, Industry $industry, Draft $draft, DraftIndustry $draft_industry, DraftIntro $draft_intro, DraftKeyword $draft_keyword, DraftBody $draft_body, Keywords $keywords, Corevalue $corevalue, Management $management, Subcom $subcom, Gallery $gallery, Video $video)
    {
        $this->user = $user;
        $this->keywordblockinfo = $keywordblockinfo;
        $this->picture = $picture;
        $this->draft = $draft;
        $this->industry = $industry;
        $this->xcgeo = $xcgeo;
        $this->draftIntro = $draft_intro;
        $this->draftKeyword = $draft_keyword;
        $this->draftIndustry = $draft_industry;
        $this->draftBody = $draft_body;
        $this->insideconnect = $insideconnect;
        $this->keywords = $keywords;
        $this->corevalue = $corevalue;
        $this->management = $management;
        $this->financing = $financing;
        $this->subcom = $subcom;
        $this->gallery = $gallery;
        $this->video = $video;
        $this->res = array(
            'status_code' => 200,
            'message' => null,
            'data' => null
        );
    }

    /**
     * @api {get} /getnewsindex 获取新闻内容信息
     * @apiDescription 获取新闻内容信息
     * @apiGroup NewsController
     * @apiPermission 否
     * @apiParam {Int}   draft_id     新闻ID
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getnewsindex
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
     *        "id": 130,
     *        "title": "标题测试111",
     *        "subtitle": "标题测试111",
     *        "author_name": "yyj",
     *        "draft_intro_list": [
     *            {
     *                "id": 36,
     *                "desc": "zxczxc"
     *            },
     *            {
     *                "id": 35,
     *                "desc": "zxczxc"
     *            }
     *        ],
     *        "draft_keyword_list": [
     *            {
     *                "keyword_code": "fCIv2fGQpo8jkUvt",
     *                "name": "太空探索技术公司",
     *                "name_en": "SpaceX",
     *                "head_path": "http://www.xincheng.tv/abc123.jpg",
     *                "desc": "探索无止境"
     *            }
     *        ],
     *        "draft_industry_list": {
     *            "industry_code": "IND0011",
     *            "industry_name": "计算机视觉",
     *            "industry_name_en": "Computer Vision",
     *            "industry_parent_name": "人工智能",
     *            "industry_parent_name_en": "Artificial Intelligence"
     *        },
     *         "draft_body_list": [
     *                  {
     *                  "id": 25931,
     *                  "element_content": "摘要",
     *                  "element_type": "intro",
     *                  "element_id": 0
     *                  },
     *                  {
     *                  "id": 25928,
     *                  "element_content": "",
     *                  "element_type": "gallery",
     *                  "element_id": 26,
     *               "picture_list": [
     *               {
     *               "id": 100,
     *               "thumb_path": "",
     *               "original_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-olRvEdHHvq.jpeg",
     *               "desc": ""
     *               }
     *               ]
     *               },
     *               {
     *               "id": 25929,
     *               "element_content": "电饭锅豆腐干地方过多过多",
     *               "element_type": "video",
     *               "element_id": 38,
     *               "video": {
     *               "id": 38,
     *               "title": "MOTO V7",
     *               "url": "XMzA0NTY5MDg4NA",
     *               "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-N73n2SvKCK.jpeg"
     *               }
     *               },
     *               {
     *                  "id": 25930,
     *                  "element_content": "",
     *                  "element_type": "picture",
     *                  "element_id": 165,
     *                  "picture_list": {
     *                  "id": 165,
     *                  "original_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-rDSPbN1IIA.jpeg",
     *                  "desc": "新媒体"
     *                }
     *            }
     *         ]
     *    }
     *}
     */


    public function GetNewsIndex(Request $req)
    {
        $validator = \Validator::make($req->all(), [
            'draft_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $req->all();
        $draft_list = $this->draft->find($attributes['draft_id']);
        if (!$draft_list) {
            $this->res['status_code'] = 201;
            $this->res['message'] = '未找到draft_id信息！';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }
        $data = array();
        $data['id'] = $draft_list['id'];
        $data['title'] = $draft_list['title'];
        $data['subtitle'] = $draft_list['subtitle'];
        $author_name = $this->user->where('id', $draft_list['author'])->value('name');
        $emial = $this->user->where('id', $draft_list['author'])->value('email');
        if (!$author_name) {
            $author_name = '新城商业';
        }
        $data['author_name'] = $author_name;
        $data['author_email'] = $emial;
        $draft_intro_list = $this->draftBody->where('draft_id', $data['id'])->where('element_type', 'intro')->orderBy('element_order', 'asc')->orderBy('created_at', 'desc')->get(['id', 'element_content as desc']);
        $data['draft_intro_list'] = iterator_to_array($draft_intro_list);
        $draft_keyword_list = $this->draftKeyword->where('draft_id', $data['id'])->orderBy('order', 'asc')->orderBy('created_at', 'desc')->get(['keyword_code']);

        $headpath = '';
        $min_ord = $min_id = 0;
        foreach ($draft_keyword_list as $key => $value) {
            $keyword = $this->keywords->where('code', $value['keyword_code'])->first(['id', 'name', 'name_en', 'head_path', 'desc', 'order']);
            $draft_keyword_list[$key]['name'] = $keyword['name'];
            $draft_keyword_list[$key]['name_en'] = $keyword['name_en'];
            $draft_keyword_list[$key]['desc'] = $keyword['desc'];
            if (intval($keyword['order']) > $min_ord) {
                $headpath = $keyword['head_path'];
            } elseif (intval($keyword['order']) == $min_id) {
                if (intval($keyword['id']) <= $min_id) {
                    $headpath = $keyword['head_path'];
                }
            }
        }
        $data['head_path'] = $headpath;

        $data['draft_keyword_list'] = $draft_keyword_list;
        $draft_industry_list = $this->draftIndustry->where('draft_id', $data['id'])->orderBy('order', 'asc')->orderBy('created_at', 'desc')->first(['industry_code']);
        $industry = $this->industry->where('code', $draft_industry_list['industry_code'])->first(['parent', 'name', 'name_en']);
        $draft_industry_list['industry_name'] = $industry['name'];
        $draft_industry_list['industry_name_en'] = $industry['name_en'];
        $parent_industry = $this->industry->where('code', $industry['parent'])->first(['parent', 'name', 'name_en']);
        $draft_industry_list['industry_parent_name'] = $parent_industry['name'];
        $draft_industry_list['industry_parent_name_en'] = $parent_industry['name_en'];
        $data['draft_industry_list'] = $draft_industry_list;

        $draft_body = $this->draftBody->where('draft_id', $data['id'])->orderBy('element_order', 'asc')->orderBy('created_at', 'desc')->get(['id', 'element_content', 'element_type', 'element_id']);

        if ($draft_body) {
            foreach ($draft_body as $key => $value) {

                switch ($value['element_type']) {
                    case 'picture':
                        $draft_body[$key]['picture_list'] = $this->picture->where('id', $value['element_id'])->orderBy('order', 'asc')->get(['id', 'original_path', 'desc']);
                        break;
                    case 'block':
                        $draft_body[$key]['block_list'] = $this->keywordblockinfo->where('id', $value['element_id'])->orderBy('order', 'asc')->first(['id', 'content']);
                        break;
                    case 'tech':
                        break;

                    case'product':
                        break;

                    case'news':
                        break;

                    case'intro':
                        break;

                    case'gallery':
                        $draft_body[$key]['picture_list'] = $this->picture->select('id', 'thumb_path', 'original_path', 'desc')->where('gallery_id', $value['element_id'])->orderBy('order', 'asc')->get();
                        break;

                    case'video':
                        $draft_body[$key]['video'] = $this->video->select('id', 'title', 'subtitle', 'url', 'thumb_path')->where('id', $value['element_id'])->orderBy('order', 'asc')->first(['id', 'content']);
                        break;

                    case'project':
                        break;
                }
            }
        }
        $data['draft_body_list'] = $draft_body;
        $keyword_draft = $this->draftKeyword->where('draft_id', $req->input('draft_id'))->pluck('keyword_code');
        if (!empty($keyword_draft)) {
            $data['draft_information'] = $this->keywords->select('name', 'name_en', 'desc')->where('code', $keyword_draft)->get();
        } else {
            $data['draft_information'] = null;
        }
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);

    }

    /**
     * @api {get} /getnewsedge 获取内容右边栏信息
     * @apiDescription 获取内容右边栏信息
     * @apiGroup NewsController
     * @apiPermission 否
     * @apiParam {Int}   draft_id     新闻ID
     * @apiParam {String} keyword_code   关键词Code 可以为空如果没有 就去找新闻ID下的关键词Code
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getnewsedge
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
     *        "keyword_info_list": {
     *            "name": "测试新闻的关键词",
     *            "oneword": "",
     *            "desc": "",
     *            "geo_code_country": "",
     *            "geo_code_province": "",
     *            "geo_code_city": "",
     *            "birth": "0000-00-00",
     *            "logo_path": "",
     *            "head_path": ""
     *        },
     *        "porduct_info_list": [
     *            {
     *                "id": 45,
     *                "keyword_code": "KW0000006",
     *                "name": "诶我去诶我去爱迪生"
     *            }
     *        ],
     *        "technology_info_list": [],
     *        "project_info_list": [],
     *        "keyfigures_info_list": [
     *            {
     *                "id": 1,
     *                "keyword_code": "KW0000006",
     *                "executive": "KW0000006",
     *                "type_code_role": "ST00002",
     *                "name": "测试新闻的关键词",
     *                "name_en": "",
     *                "role_name": "优酷",
     *                "role_name_en": ""
     *            }
     *        ],
     *        "m_subcom_info_list": null,
     *        "subcom_info_list": {
     *            "id": 9,
     *            "keyword_code": "cGuVNvXBugGaFvxk",
     *            "sub_keyword_code": "KW0000006",
     *            "is_passed": 1,
     *            "is_published": 1,
     *            "is_disabled": 0,
     *            "is_locked": 0,
     *            "order": 9,
     *            "created_at": null,
     *            "name": "测试新闻的关键词",
     *            "name_en": "",
     *            "code": "KW0000006",
     *            "keyword_category_code": "KC002",
     *            "oneword": "",
     *            "desc": "",
     *            "geo_code_country": "",
     *            "geo_code_province": "",
     *            "geo_code_city": "",
     *            "birth": "0000-00-00",
     *            "logo_path": "",
     *            "head_path": "",
     *            "news_id": 0,
     *            "source_app": "",
     *            "source_author": 0
     *        },
     *        "investor_info_list": [
     *            {
     *                "name": "特",
     *                "oneword": "",
     *                "desc": "",
     *                "geo_code_country": "1",
     *                "geo_code_province": "101011",
     *                "geo_code_city": "105495081310101101",
     *                "birth": "0000-00-00",
     *                "logo_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-YN9As8fFw6.png",
     *                "head_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-F8KgV83cMF.png"
     *            },
     *            {
     *                "name": "入网费",
     *                "oneword": "",
     *                "desc": "",
     *                "geo_code_country": "DZA",
     *                "geo_code_province": "",
     *                "geo_code_city": "",
     *                "birth": "0000-00-00",
     *                "logo_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-kgDunbTNCX.png",
     *                "head_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-v9ixYWgGdH.png"
     *            }
     *        ]
     *    }
     *}
     */
    public function GetnewsEdge(Request $req)
    {
        $validator = \Validator::make($req->all(), [
            'draft_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $req->all();

        $draft_list = $this->draft->find($attributes['draft_id']);
        if (!$draft_list) {
            $this->res['status_code'] = 201;
            $this->res['message'] = '未找到draft_id信息！';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }

        $data = array();
        $xc_keyword_code = array();
        if (isset($attributes['keyword_code'])) {
            $xc_keyword_code = $this->draftKeyword->where('keyword_code', $attributes['keyword_code'])->first(['id', 'keyword_code']);
        } else {
            $xc_keyword_code = $this->draftKeyword->where('draft_id', $attributes['draft_id'])->first(['id', 'keyword_code']);
        }

        $keyword_info_list = $this->keywords->where('code', $xc_keyword_code['keyword_code'])
            ->first(['name_en', 'name', 'oneword', 'desc', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'birth', 'logo_path', 'head_path']);

        $keyword_info_list['geo_list'] = array('country' => $this->xcgeo->where('code', $keyword_info_list['geo_code_country'])->value('name'),
            'province' => $this->xcgeo->where('code', $keyword_info_list['geo_code_province'])->value('name'),
            'city' => $this->xcgeo->where('code', $keyword_info_list['geo_code_city'])->value('name')
        );

        $data['keyword_info_list'] = $keyword_info_list;

        $data['porduct_info_list'] = $this->corevalue->where('keyword_code', $xc_keyword_code['keyword_code'])
            ->where('type', 'p')
            ->get(['id', 'keyword_code', 'name_en', 'name']);

        $data['technology_info_list'] = $this->corevalue->where('keyword_code', $xc_keyword_code['keyword_code'])
            ->where('type', 't')
            ->get(['id', 'keyword_code', 'name']);

        $data['project_info_list'] = $this->corevalue->where('keyword_code', $xc_keyword_code['keyword_code'])
            ->where('type', 's')
            ->get(['id', 'keyword_code', 'name']);

        $data['keyfigures_info_list'] = $this->management->leftJoin('sys_keyword', 'xc_management.executive', '=', 'sys_keyword.code')
            ->leftJoin('sys_type', 'xc_management.type_code_role', '=', 'sys_type.code')
            ->where('keyword_code', $xc_keyword_code['keyword_code'])
            ->get(['xc_management.id', 'xc_management.keyword_code', 'executive', 'xc_management.type_code_role', 'sys_keyword.name', 'sys_keyword.name_en', 'sys_type.name as role_name', 'sys_type.name_en as role_name_en']);


        $data['m_subcom_info_list'] = $this->subcom->where('keyword_code', $xc_keyword_code['keyword_code'])->leftJoin('sys_keyword', 'xc_subcom.keyword_code', '=', 'sys_keyword.code')->first();

        $data['subcom_info_list'] = $this->subcom->where('sub_keyword_code', $xc_keyword_code['keyword_code'])->leftJoin('sys_keyword', 'xc_subcom.sub_keyword_code', '=', 'sys_keyword.code')->get();

        $data['investor_info_list'] = null;
        $query = $this->financing->select('investor')->where('keyword_code', $xc_keyword_code['keyword_code'])->leftJoin('data_investor', 'data_financing.id', '=', 'data_investor.financing_id')->distinct()->get();

        foreach ($query as $key => $value) {
            $data['investor_info_list'][$key] = $this->keywords->where('code', $value['investor'])
                ->first(['id', 'name', 'oneword', 'desc', 'geo_code_country', 'geo_code_province', 'geo_code_city', 'birth', 'logo_path', 'head_path']);
        }
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }

    /**
     * @api {get} /getallnewslist 获取新闻相关所有新闻
     * @apiDescription 获取新闻相关所有新闻
     * @apiGroup NewsController
     * @apiPermission 否
     * @apiParam {Int}   draft_id     新闻ID
     * @apiHeader {String} Accept  application/vnd.lumen.v1+json
     * @apiSampleRequest  http://appapi.xincheng.tv/api/getallnewslist
     * @apiVersion 0.0.1
     * @apiHeaderExample {String} Header-Example:
     *     {
     *       "Accept": "application/vnd.lumen.v1+json"
     *     }
     * @apiSuccessExample {json} Success-Response:
     *{
     * "status_code": 200,
     * "message": null,
     * "data": {
     * "news_list": [
     * {
     * "draft_id": 54,
     * "keyword_code": "Oo7M3jwwclORI3Pa",
     * "title": "自行车自行车",
     * "subtitle": "自行车自行车",
     * "thumb_path": null
     * },
     * {
     * "draft_id": 168,
     * "keyword_code": "Oo7M3jwwclORI3Pa",
     * "title": "",
     * "subtitle": "",
     * "thumb_path": null
     * },
     * {
     * "draft_id": 157,
     * "keyword_code": "Oo7M3jwwclORI3Pa",
     * "title": "标题测试11211s",
     * "subtitle": "标测11121ss",
     * "thumb_path": null
     * }
     * ]
     * }
     * }
     */
    public function getAllNewsList(Request $req)
    {
        $validator = \Validator::make($req->all(), [
            'draft_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $req->all();

        $draft_list = $this->draft->find($attributes['draft_id']);
        if (!$draft_list) {
            $this->res['status_code'] = 201;
            $this->res['message'] = '未找到draft_id信息！';
            return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
        }
        $data = array();

        $draft_keyword = $this->draftKeyword->where('draft_id', $attributes['draft_id'])->first(['keyword_code']);
        $data['news_list'] = $this->draftKeyword->where(['keyword_code' => $draft_keyword['keyword_code']])->leftJoin('draft', 'draft_keyword.draft_id', '=', 'draft.id')->get(['draft_id', 'keyword_code', 'title', 'subtitle', 'thumb_path']);
        $this->res['data'] = $data;
        return $this->response->array($this->res)->setStatusCode($this->res['status_code']);
    }


    /**
     * @api {post} /getgallery 图库信息
     * @apiDescription 图库信息
     * @apiGroup Gallery
     * @apiPermission 需要
     * @apiParam {Int}    news_id   新闻ID
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     * {
     *         "status_code": 200,
     *         "message": "获取成功！",
     *         "data": {
     *              "id": 26,
     *              "title": "2345",
     *              "keyword_name": "扎克伯格",
     *              "industry_name": "多媒体处理技术",
     *              "picture": [
     *                  {
     *                      "id": 100,
     *                      "original_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-olRvEdHHvq.jpeg"
     *                  }
     *               ],
     *               "picture_count": 1
     *         }
     * }
     */
    public function getGallery(Request $request)
    {
        $id = $request->input('news_id');
        $gallery_data = $this->draftBody->where('draft_id', $id)->first();
        if (!is_null($gallery_data) && $gallery_data->element_type == 'gallery') {

            $gallery_id = $gallery_data->element_id;
            $gallery = $this->gallery->getGalleryInfo(['xc_gallery.id' => $gallery_id], ['xc_gallery.id', 'xc_gallery.title', 'sys_keyword.name as keyword_name', 'sys_industry.name as industry_name']);
            if (!is_null($gallery)) {
                $gallery->picture = $this->picture->getAllPicture(['gallery_id' => $gallery_id], ['id', 'original_path']);
                $gallery->picture_count = $this->picture->where('gallery_id', $gallery_id)->count();
            }

        } else {
            $gallery = null;
        }
        $this->res['status_code'] = 200;
        $this->res['message'] = '获取成功！';
        $this->res['data'] = $gallery;
        return $this->res;
    }


}