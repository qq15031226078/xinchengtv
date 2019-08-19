<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V2;
use DB;
use Illuminate\Http\Request;

class CorevalueController extends CommonController
{
    /**
     * 返回数据方法: responseFromat(错误码, 返回数据, 自定义消息)
     * 跨库查询: DB::connection('writing')->table('draft')->get();
     *
     */
    /**
     * @api {get} /v2/core 频道：产科项
     * @apiDescription 频道：产科项 - 马骏飞
     * @apiGroup Channel
     * @apiPermission 需要
     * @apiParam {String} industry_code 所属行业类型编码  [注意:非必传但需要修改时必传] 参数示例：单个:IND0023 多个IND0023,IND0024
     * @apiParam {String} type   类型 p:产品;t:科技;s:项目;
     * @apiParam {Int} page   页数
     * @apiParam {Int} per_page   每页显示条数
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "statusCode": 4000,
     *      "statusMessage": "获取数据成功",
     *      "responseData": {
     *          "total": 37,
     *          "per_page": 5,
     *          "current_page": 1,
     *          "last_page": 8,
     *          "next_page_url": "http://api.xc.serverapi.cn:8282/v1/corevalue?type=1&industryCode=IND0023&page=2",
     *          "prev_page_url": null,
     *          "from": 1,
     *          "to": 5,
     *          "data": [
     *              {
     *                  "id": 28,  // 产科项id
     *                  "name": "龙飞船", // 产科项中文名
     *                  "name_en": "SpaceX Dragon", // 产科项英文名
     *                  "thumb_path": "http://uploads2.b0.upaiyun.com///prod/prod1499822420.jpg!ptpe.img", // 产科项缩略图
     *                  "keyword_code": "W1UN4YbN4pKnuvR6", // 关键词code
     *                  "keyword_name": "太空探索技术公司", // 关键词名称
     *                  "keyword_name_en": "SpaceX",    // 关键词英文名
     *                  "keyword_logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1495701352.jpg!initial" // 关键词缩略图
     *              }
     *          ]
     *      }
     * }
     */


    /**
     * @api {get} /v2/company 频道：公司机构
     * @apiDescription 频道：公司机构 - 马骏飞
     * @apiGroup Channel
     * @apiPermission 需要
     * @apiParam {String} industry_code 所属行业类型编码  [注意:非必传但需要修改时必传] 参数示例：单个:IND0023 多个IND0023,IND0024
     * @apiParam {String} type   类型 KC002:公司;KC004:机构
     * @apiParam {Int} page   页数
     * @apiParam {Int} per_page   每页显示条数
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "statusCode": 4000,
     *      "statusMessage": "获取数据成功",
     *      "responseData": {
     *          "total": 37,
     *          "per_page": 5,
     *          "current_page": 1,
     *          "last_page": 8,
     *          "next_page_url": "http://api.xc.serverapi.cn:8282/v1/corevalue?type=1&industryCode=IND0023&page=2",
     *          "prev_page_url": null,
     *          "from": 1,
     *          "to": 5,
     *          "data": [
     *              {
     *                  "code": "cs8dBG4I9jSz7tj5", // 关键词code
     *                  "name_cn": "Rocket Crafters",   // 关键词中文名
     *                  "name_en": "Rocket Crafters",   // 关键词英文名
     *                  "category_code": "KC002",   // 关键词类型
     *                  "oneword": "火箭技术研发商",   // 关键词一句话描述
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1495158214.jpg!initial"  // 关键词缩略图
     *              }
     *          ]
     *      }
     * }
     */

    /**
     * @api {get} /v2/people 频道：人物
     * @apiDescription 频道：人物 - 马骏飞
     * @apiGroup Channel
     * @apiPermission 需要
     * @apiParam {String} industry_code 所属行业类型编码 可传一级或者二级  [注意:非必传但需要修改时必传] 参数示例：单个:IND0023 多个IND0023,IND0024
     * @apiParam {String} type   类型 KC003:人物
     * @apiParam {Int} page   页数
     * @apiParam {Int} per_page   每页显示条数
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "statusCode": 4000,
     *      "statusMessage": "获取数据成功",
     *      "responseData": {
     *          "total": 37,
     *          "per_page": 5,
     *          "current_page": 1,
     *          "last_page": 8,
     *          "next_page_url": "http://api.xc.serverapi.cn:8282/v1/corevalue?type=1&industryCode=IND0023&page=2",
     *          "prev_page_url": null,
     *          "from": 1,
     *          "to": 5,
     *          "data": [
     *              {
     *                  "code": "cs8dBG4I9jSz7tj5", // 关键词code                                                         
     *                  "name_cn": "Rocket Crafters",   // 关键词中文名                                                      
     *                  "name_en": "Rocket Crafters",   // 关键词英文名                                                      
     *                  "category_code": "KC002",   // 关键词类型                                                           
     *                  "oneword": "火箭技术研发商",   // 关键词一句话描述
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1495158214.jpg!initial"  // 关键词缩略图
     *                  "company": "谷歌",     // 公司
     *                  "role": "创始人"       // 职称
     *              },
     *          ]
     *      }
     * }
     */
    public function getContentItem(Request $request)
    {
        preg_match('/\/v2\/(.*)\?/', $request->getRequestUri(), $match);

        $vaildateParams = [
            'people' => 'KC003',
            'core' => 'p,t,s',
            'company' => 'KC002,KC004',
        ];

        if(!isset($match[1])) return $this->responseFromat(501);

        $this->validate($request, [
            'industry_code' => 'required|string',
            'type' => 'required|in:'.$vaildateParams[$match[1]]
        ]);

        $codes = DB::table('sys_industry')->select('code')->where([
            'parent'=>$request->get('industry_code'),'deleted_at'=>NULL
        ])->get();

        if(strpos($request->get('industry_code'), ',')) {
            $where = explode(',', $request->get('industry_code'));
        } else {

            $where = [$request->get('industry_code')];

            if(count($codes)&& $results = $codes->toArray()) foreach($results as $val) $where[] = $val->code;
        }

        $whereParmaConfig = [
            'p' => ['a.product_id', '!=', '0'],
            't' => ['a.tech_id', '!=', '0'],
            's' => ['a.project_id', '!=', '0'],
            'KC002' => ['c.keyword_category_code', '=', 'KC002'],
            'KC003' => ['c.keyword_category_code', '=', 'KC003'],
            'KC004' => ['c.keyword_category_code', '=', 'KC004']
        ][$request->get('type')];

        $tableParams = [
            'p' => 'xc_core_value_index as a',
            't' => 'xc_core_value_index as a',
            's' => 'xc_core_value_index as a',
            'KC002' => 'sys_keyword_industry_ref as a',
            'KC003' => 'sys_keyword_industry_ref as a',
            'KC004' => 'sys_keyword_industry_ref as a'
        ];

        $select = [
            'p' => [
                'a.product_id as id', 'b.name as name', 'b.name_en as name_en',
                'b.thumb_path as thumb_path', 'c.code as keyword_code', 'c.name as keyword_name',
                'c.name_en as keyword_name_en', 'c.logo_path as keyword_logo_path'
            ],
            't' => [
                'a.tech_id as id', 'b.name as name', 'b.name_en as name_en',
                'b.thumb_path as thumb_path', 'c.code as keyword_code', 'c.name as keyword_name',
                'c.name_en as keyword_name_en', 'c.logo_path as keyword_logo_path'
            ],
            's' => [
                'a.project_id as id', 'b.name as name', 'b.name_en as name_en',
                'b.thumb_path as thumb_path', 'c.code as keyword_code', 'c.name as keyword_name',
                'c.name_en as keyword_name_en', 'c.logo_path as keyword_logo_path'
            ],
            'KC002' => [
                'c.code as code', 'c.name as name_cn', 'c.name_en as name_en',
                'c.keyword_category_code as category_code', 'c.oneword as oneword', 'c.logo_path as logo_path'
            ],
            'KC003' => [
                'c.code as code', 'c.name as name_cn', 'c.name_en as name_en',
                'c.keyword_category_code as category_code', 'c.oneword as oneword', 'c.logo_path as logo_path'
            ],
            'KC004' => [
                'c.code as code', 'c.name as name_cn', 'c.name_en as name_en',
                'c.keyword_category_code as category_code', 'c.oneword as oneword', 'c.logo_path as logo_path'
            ]
        ];

        if(in_array($request->get('type'), ['p','t','s'])) {
            $response = DB::table($tableParams[$request->get('type')])->whereIn('a.industry_code', $where)->where(
                $whereParmaConfig[0], $whereParmaConfig[1], $whereParmaConfig[2]
            )->join('xc_core_value as b', $whereParmaConfig[0], '=', 'b.id', 'left')->select($select[$request->get('type')])
            ->join('sys_keyword as c', 'c.code', '=', 'b.keyword_code', 'left')
            ->paginate($request->get('per_page', 5));
        }else{
            $response = DB::table($tableParams[$request->get('type')])->whereIn('a.industry_code', $where)->where(
                $whereParmaConfig[0], $whereParmaConfig[1], $whereParmaConfig[2]
            )->join('sys_keyword as c', 'c.code', '=', 'a.keyword_code', 'left')->select($select[$request->get('type')])
            ->paginate($request->get('per_page', 5));
        }

        $response->appends($request->all());

        if(count($response)){
            $response = json_decode(json_encode($response), true);
            if($request->get('type') == 'KC003') {
                foreach ($response['data'] as $Key => $Val) {
                    $Val['logo_path'] = $Val['logo_path'].'!draft650';
                    $response['data'][$Key] = array_merge($Val, collect($this->keywordRole($Val['code']))->toArray());
                }
            }
            $addDraft = ['s','t','p'];
            if(in_array($request->get('type'), $addDraft)) {
                foreach ($response['data'] as $key => $va) {
                    $response['data'][$key]['thumb_path'] = $va['thumb_path'].'!draft650';
                }
            }
            $code = 200;
            $message = '';
        } else{
            $code = 501;
            $response = null;
            $message = '没有更多数据';
        }

        return $this->responseFromat($code, $response, $message);
    }

    /**
     * @api {get} /v2/keyword/intro 关键词：简介
     * @apiDescription 关键词：简介 - 马骏飞
     * @apiGroup Keyword
     * @apiPermission 需要
     * @apiParam {String} keyword_code 关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *  {
     *      "statusCode": 200,
     *      "statusMessage": "获取数据成功",
     *      "responseData": {
     *          "keywords": {
     *                  "code": "W1UN4YbN4pKnuvR6", //关键词code
     *                  "name": "太空探索技术公司", // 关键词中文名
     *                  "name_en": "SpaceX",    // 关键词英文名
     *                  "keyword_category_code": "KC002",   //关键词类型id
     *                  "oneword": "太空运输服务商",   // 关键词一句话描述
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1495701352.jpg!initial", // 关键词缩略图
     *                  "head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1495701352.jpg!news.logo.500.270", // 关键词头图
     *                  "desc": "SpaceX于2002年成立于美国加州，是一家太空运输服务公司、火箭回收技术研发商，创始人为埃隆·马斯克。",
     *                  "birth_info": "成立于：2002年06月" // 生日信息
     *              },
     *              "blocks": [
     *                  {
     *                      "id": 152,  // 信息块id
     *                      "title": "失败统计",    // 信息块标题
     *                      "contents": [   // 信息条
     *                          "截止2017年5月初，SpaceX在当年的五次发射任务均获成功。",
     *                          "猎鹰1号共5次发射任务中，有3次失败，直到第四次才首次成功。",
     *                          "猎鹰9号从2010年6月4日首次任务至2017年5月1日的七年中，共执行了33次任务，有9次失败，其中2016年失败4次。",
     *                          "在2016年9月的发射任务中，猎鹰9号在发射前例行测试时发生爆炸。",
     *                          "2017年1月，SpaceX走出爆炸的阴影，进行爆炸后的首次发射。"
     *                      ]
     *              }
     *          ],
     *          "gallery": [
     *              {
     *                  "id": 1,    // 图库id
     *                  "title": "Solar Probe Plus太阳探测器",   // 图库标题
     *                  "picture": [    // 图库里的图片集合
     *                      {
     *                          "desc": "", // 图片描述
     *                          "thumb_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270", // 图片缩略图
     *                          "original_path": "http://uploads2.b0.upaiyun.com//gallery/gallery1496484946.jpg!news.logo.500.270" // 图片原图
     *                      }
     *                  ]
     *              }
     *          ]
     *      }
     *  }
     */
    public function KeywordIntro(Request $request){
        $this->validate($request, [
            'keyword_code' => 'required|string',
        ]);

        $keyword = DB::table('sys_keyword')->select([
            'code','name','name_en','keyword_category_code','oneword','logo_path','head_path', 'desc'
        ])->where(['code'=>$request->get('keyword_code')])->first();

        if($keyword === null) return $this->responseFromat(501, [], '未找到该数据');

        $keyword = json_decode(json_encode($keyword), true);

        $keyword['birth_info'] = $this->keywordFound($keyword['code']);
        $data['keywords'] = $keyword;
        $data['blocks'] = $this->blockInfo($keyword['code']);

        $keywordsItem = DB::table('db_writing.draft_keyword')->orderBy('id','desc')->limit(3)
            ->select('draft_id')->where(['keyword_code' => $request->get('keyword_code'),])->get();

        if(count($keywordsItem)) {
            $code = 200;
            $message = '获取数据成功';
            $data['gallery'] = $this->keywordGallerys($request->get('keyword_code'));
        }else{
            $code = 501;
            $message = '未找到该数据';
            $data['gallery'] = [];
        }

        return $this->responseFromat($code, $data, $message);
    }
}
