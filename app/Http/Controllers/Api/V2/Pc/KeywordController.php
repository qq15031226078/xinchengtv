<?php

namespace App\Http\Controllers\Api\V2\Pc;

use App\Models\Keywords;

use DB;
use Illuminate\Http\Request;

class KeywordController extends CommonController
{

    /**
     * @api {get} /pc/v2/keyword/info 关键词信息
     * @apiDescription 关键词信息------------------------------王磊
     * @apiGroup PC
     * @apiPermission 需要验证
     * @apiParam {int}      keyword_code  关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "keyword": {
     *                  "name": "谷歌",
     *                  "name_en": "Google",
     *                  "code": "KEY000004",
     *                  "oneword": "全球三大科技巨头之一",
     *                  "city_info": "美国",
     *                  "birth_info": "1998年09月07日",
     *                  "logo_path": "http://uploads2.b0.upaiyun.com//keywords1/key1500707165.jpg",
     *                  "head_path": "http://uploads2.b0.upaiyun.com///keywords2/key1491466881.jpg"
     *              },
     *              "product": [
     *                  {
     *                      "core_id": 182,
     *                      "name": "张量处理单元"
     *                  },
     *              ],
     *              "tech": [
     *                  {
     *                      "core_id": 1268,
     *                      "name": "计算机视觉模型"
     *                  },
     *              ],
     *              "project": [
     *                  {
     *                      "core_id": 1268,
     *                      "name": "计算机视觉模型"
     *                  },
     *              ],
     *              "people": [
     *                  {
     *                      "id": 62,
     *                      "keyword_code": "KEY000254",
     *                      "name": "杰弗里·辛顿",
     *                      "name_en": "Geoffrey Everest Hinton",
     *                      "role": "CXO"
     *                  },
     *              ]
     *          }
     *      }
     */ 
    public function getkeywordinfo(Request $request)
    {
        $val = $request->all();
        $validator = \Validator::make($val, [
            'keyword_code' => 'required',
        ]);
        if ( $validator->fails() ) return $this->responseFromat(501,$validator);
        $data = $this->keyinfo($val['keyword_code']);
        if($data) {
            return $this->responseFromat(200,$data);
        } else {
            return $this->responseFromat(400);
        }
    }


    public function getkeywordinfoa(Request $request)
    {
    	$val = $request->all();
        $validator = \Validator::make($val, [
            'keyword_code' => 'required',
        ]);
        if ( $validator->fails() ) return $this->responseFromat(501,$validator);
        $keywordinfos = DB::table('sys_keyword')->
                                where('code', $val['keyword_code'])->
                                select(['name','name_en','oneword','desc','birth','logo_path'])->
                                first();
        if($keywordinfos) {
            $keywordinfos->gallerys  = $this->keywordGallerys($val['keyword_code']);
            $keywordinfos->blockInfo = $this->blockInfo($val['keyword_code']);
        }
        return $this->responseFromat(200,$keywordinfos);
    }


}