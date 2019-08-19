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
     * @apiParam {int}	limit			每页显示数量
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *  {
	 *	  "statusCode": 200,
	 *	  "statusMessage": "获取数据成功",
	 *	  "responseData": {
	 *	    "total": 118,
	 *	    "per_page": "1",
	 *	    "current_page": 1,
	 *	    "last_page": 118,
	 *	    "next_page_url": "http://www.xcapp.com/v1/program?page=2",
	 *	    "prev_page_url": null,
	 *	    "from": 1,
	 *	    "to": 1,
	 *	    "data": [
	 *	      {
	 *	        "title": "打针竟然改在肠道中进行",	// 视频标题
	 *	        "issue": "第118期",					// 期数
	 *	        "thumb_path": "http://uploads2.b0.upaiyun.com///vb/vb1506045098.jpg",	// 图片路径
	 *	        "type_code_website": null,			// 来自网站
	 *	        "link": "XMzAzODM0NzczNg"			// 视频连接
	 *	      }
	 *	    ]
	 *	  }
	 *	}
     */
    public function Program(Request $req){
    	$val = $req->all();
    	if (empty($val['program_type'])) return $this->responseFromat(530,[],'缺少program_type参数');
    	if (!is_numeric($val['program_type'])) return $this->responseFromat(510,[],'program_type必须是数字');
    	$limit = empty($val['limit']) ? 5 : $val['limit'];
    	if (!is_numeric($limit)) return $this->responseFromat(510,[],'limit必须是数字');
    	$program = DB::table('xc_program')->where(['xc_program.program_type'=>$val['program_type'], 'xc_program.is_published'=>1])->orderBy('xc_program.issue','desc')->orderBy('xc_program.created_at','desc')->select('xc_program.title','xc_program.issue','xc_program.thumb_path','sys_type.name as type_code_website','xc_program.link')->leftJoin('sys_type', 'xc_program.type_code_website', '=', 'sys_type.code')->paginate($limit);
		if(count($program) == 0) return $this->responseFromat(501, [], '没有更多数据');
    	foreach ($program as $k => $v) {
            $program[$k]->thumb_path = $v->thumb_path."!pro700.300";
    		$v->issue = '第'.$v->issue.'期';
    	}
    	return $this->responseFromat(200, $program);
    }
}