<?php
namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\V2;
use DB;
use Illuminate\Http\Request;

class IndustryController extends CommonController
{
    
	/**
     * @api {get} /v2/navigation 频道导航
     * @apiDescription 频道导航------------------------------------王磊
     * @apiGroup Main
     * @apiPermission 需要验证
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": [
     *              {
     *                  "code": "IND0002", // 一级行业code
     *                  "name": "基础物理", // 一级行业中文名
     *                  "name_en": "Fundamental Physics", // 一级行业英文名
     *                  "second_industry": [ 
     *                      {
     *                          "code": "IND0097", // 二级行业code
     *                          "name": "热力学", // 二级行业中文名
     *                          "name_en": "" // 二级行业英文名
     *                      },
     *                      {
     *                          "code": "IND0098",
     *                          "name": "量子力学",
     *                          "name_en": ""
     *                      },
     *                      {
     *                          "code": "IND0099",
     *                          "name": "电磁力学",
     *                          "name_en": ""
     *                      }
     *                  ]
     *              }
     *          ]
     *      }
     */
     public function navigation(Request $req)
     {
        $data = DB::table('sys_industry')->where(['parent'=>'', 'deleted_at'=>null])->orderBy('order')->select(['code','name','name_en'])->get();
        foreach($data as $key => $val) {
            $val->second_industry = DB::table('sys_industry')->where(['parent'=>$val->code, 'deleted_at'=>null])->orderBy('order','desc')->select(['code','name','name_en'])->get();
        }
    	return $this->responseFromat(200,$data);
    }

}
