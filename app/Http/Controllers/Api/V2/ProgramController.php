<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProgramController extends CommonController
{
	public function __construct()
    {
        $this->res = array(
            'status_code' => 200,
            'message' => null,
            'data' => null
        );
    }

	/**
     * @api {get} /v2/program 节目
     * @apiDescription 节目 ———— 朱朔男
     * @apiGroup Main
     * @apiPermission 需要验证
     * @apiParam {int}	program_type	节目类型:1-新城商业(默认),2-像素科技
     * @apiParam {int}  limit           每页显示数量
     * @apiParam {int}	id			    节目ID（传了显示单个）
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *      {
     *          "statusCode": 200,
     *          "statusMessage": "获取数据成功",
     *          "responseData": {
     *              "current_page": 1,
     *              "data": [
     *                  {
     *                      "id": 230,
     *                      "title": "量子计算机大神挑战巨头",
     *                      "issue": "第131期",
     *                      "thumb_path": "http://xincheng-img.b0.upaiyun.com/xincheng-img-5HkQ9QnjC1.jpeg!pro700.300",
     *                      "type_code_website": "七牛",
     *                      "link": "http://oze13tnr1.bkt.clouddn.com/1c2f07kfo_to1gr4dqgjg.mp4",
     *                      "date": ""
     *                  },
     *              ],
     *              "from": 1,
     *              "last_page": 4,
     *              "next_page_url": "http://www.localxinchengapp.com/v2/program?page=2",
     *              "path": "http://www.localxinchengapp.com/v2/program",
     *              "per_page": 5,
     *              "prev_page_url": null,
     *              "to": 5,
     *              "total": 20
     *          }
     *      }
     */
    public function Program(Request $req){
    	$val = $req->all();

    	if (empty($val['program_type'])) return $this->responseFromat(530,[],'缺少program_type参数');
    	if (!is_numeric($val['program_type'])) return $this->responseFromat(510,[],'program_type必须是数字');
    	$limit = empty($val['limit']) ? 5 : $val['limit'];
    	if (!is_numeric($limit)) return $this->responseFromat(510,[],'limit必须是数字');
        $where['xc_program.program_type'] = $val['program_type'];
        $where['xc_program.is_published'] = 1;
        if(!empty($val['id'])) $where['xc_program.id'] = $val['id'];
    	$program = DB::table('xc_program')->
                            where($where)->
                            orderBy('xc_program.issue','desc')->
                            orderBy('xc_program.created_at','desc')->
                            select('xc_program.id','xc_program.title','xc_program.issue','xc_program.thumb_path','sys_type.name as type_code_website','xc_program.link','xc_program.created_at')->
                            leftJoin('sys_type', 'xc_program.type_code_website', '=', 'sys_type.code')->
                            paginate($limit);

		if(count($program) == 0) return $this->responseFromat(501, [], '没有更多数据');
    	foreach ($program as $k => $v) {
            $program[$k]->thumb_path = $v->thumb_path."!pro700.300";
    		$v->issue = '第'.$v->issue.'期';
            $date = substr($v->created_at,0,10);
            $v->date = str_replace("-",".",$date);
            unset($v->created_at);
    	}
    	return $this->responseFromat(200, $program);
    }


}