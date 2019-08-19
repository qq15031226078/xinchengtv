<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Keywords;

use Illuminate\Http\Request;
use DB;

class PeopleController extends CommonController
{
    protected $keywords;
    public function __construct(Keywords $keywords)
    {
        $this->keywords = $keywords;
    }

	/**
     * @api {get} /v2/keyword/people 关键词：人物 
     * @apiDescription 关键词：人物------------------------------------王磊
     * @apiGroup Keyword
     * @apiPermission 需要验证
     * @apiParam {string}	keyword_code	关键词code（W1UN4YbN4pKnuvR6，2ig9m3grokbYsYdD，ucU2fdKmdqW00O2P，nL9TFlR4CFDcUY6U，jqcFxIzHBVU4VStV，1dXpLvKervsLI9hY，7cGL4HlQMntNy1cC，ZQ466shCj9Nj3ajO）
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "keyword": {
     *                  "code": "W1UN4YbN4pKnuvR6",   // 关键词code
     *                  "name_cn": "太空探索技术公司",  // 关键词中文名
     *                  "name_en": "SpaceX",  // 关键词英文名
     *                  "category_code": "KC002",  // 关键词类型code
     *                  "oneword": "太空运输服务商",  // 关键词一句话
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1495701352.jpg!initial",   // 关键词缩略图
     *                  "head_path": "http://uploads2.b0.upaiyun.com//keywords2/key1495701352.jpg!news.logo.500.270",  // 关键词头图
     *                  "desc": "SpaceX于2002年成立于美国加州，是一家太空运输服务公司、火箭回收技术研发商，创始人为埃隆·马斯克。", // 关键词简介
     *                  "birth_info": "成立于：2002年06月" // 地点时间 
     *              },
     *              "people": [
     *                  {
     *                      "code": "NvXUKMnqdD2FVol6",  // 人物关键词code
     *                      "name_cn": "伊隆·马斯克",  // 人物关键词中文名
     *                      "name_en": "Elon Musk",  // 人物关键词英文名
     *                      "head_path": "",  // 人物关键词头图
     *                      "role": "CEO"  // 人物关键词职务
     *                      "desc": // 人物介绍  "PayPal、OpenAI联合创始人，The Boring Company创始人，现任SpaceX CEO，参与创立Neuralink，是一位连续创业者、第一位提出超级高铁构想者。现任特斯拉CEO、SpaceX CEO、The Boring Company CEO、SolarCity董事会主席。",
     *                      "gallerys": [ // 关键词图库
     *                          {
     *                              "id": 1, // 关键词图库ID
     *                              "title": "Solar Probe Plus太阳探测器", // 关键词图库标题
     *                              "picture": [ // 关键词图片
     *                                  {
     *                                      "desc": "", // 关键词图片简介
     *                                      "thumb_path": "http://uploads2.b0.*upaiyun.om//gallery/gallery1496484946.jpg!news.logo.500.270", // 关键词图片缩略图
     *                                      "original_path": "http://uploads2.b0.*upaiyun.om//gallery/gallery1496484946.jpg!news.logo.500.270" // 关键词图片大图
     *                                  }
     *                              ]
     *                          },
     *                      ],
     *                      "block": [ // 关键词信息块
     *                          {
     *                              "block_id": 904, // 关键词信息块ID
     *                              "title": "业务方向", // 关键词信息块标题
     *                              "info": [ // 关键词信息块内容
     *                                  {
     *                                      "content": "从1958年始，美国宇航局负责了美国的太空探索，例如登月的阿波罗计划，太空实验室，以及随后的航天飞机。" // 关键词信息
     *                                  },
     *                                  {
     *                                      "content": "自2006年2月，NASA的愿景是“开拓未来的太空探索，科学发现及航空研究”；使命是“理解并保护我们依赖生存的行星；探索宇宙，找到地球外的生命；启示我们的下一代去探索宇宙”。"
     *                                  }
     *                              ]
     *                          }
     *                      ]
     *                  }
     *              ]
     *          }
     *      }
     */
    public function keywordpeople(Request $req)
    {
        $value = $req->all();
        if (empty($value['keyword_code'])) return $this->responseFromat(4300,[],'缺少参数！');
        $data['keywords'] = $this->keywordInfo($value['keyword_code']);
        $data['people'] = DB::table('xc_management')
                                ->where(['keyword_code'=>$value['keyword_code'], 'xc_management.deleted_at'=>null])
                                ->leftJoin('sys_keyword','xc_management.executive','=','sys_keyword.code')
                                ->leftJoin('sys_type','xc_management.type_code_role','=','sys_type.code')
                                ->select(['sys_keyword.code as code','sys_keyword.name as name_cn','sys_keyword.name_en as name_en','logo_path','sys_type.name as role','desc'])
                                ->get();
        foreach($data['people'] as $key => $val) {
            $data['people'][$key]->head_path = $val->logo_path."!900x480";
            unset($data['people'][$key]->logo_path);
            $data['people'][$key]->gallerys = $this->keywordGallerys($val->code);
            $data['people'][$key]->block = $this->keywordpeopleinfo($val->code);
        }
        return $this->responseFromat(200,$data);
    }


    public function keywordpeopleinfo($code)
    {
        if (empty($code)) return false;
        $block = DB::table('data_keyword_block')->where(['keyword_code'=>$code, 'deleted_at'=>null])->orderBy('order')->select(['id as block_id','title'])->get();
        foreach($block as $key => $va) {
            $picture = DB::table('data_keyword_block_info')->orderBy('order')->where(['block_id'=>$va->block_id, 'deleted_at'=>null])->get(['content']);
            $block[$key]->info = $picture;
        }
        return $block;
    }



}
