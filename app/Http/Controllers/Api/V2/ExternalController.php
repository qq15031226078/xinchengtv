<?php
namespace App\Http\Controllers\Api\V2;

use DB;
use Illuminate\Http\Request;

class ExternalController extends CommonController
{

	/**
     * @api {get} /v2/xcinfo  获取新城新闻内容
     * @apiDescription 获取新城新闻内容------------------------------------王磊
     * @apiGroup Xcinfo
     * @apiPermission 不需要验证
     * @apiParam {string}	ref_type		关联外键类型： s-超级标签 c-公司家谱 u-学生时代 t-科技星球 b-美好星球
     * @apiParam {string}	object_type		'v'-视频,'n'-新闻,'p'-图文,'x'-新城商业,'m'-音乐,'t'-电影预告片
     * @apiParam {string}	object_id		关联ID
     * @apiParam {string}	keyword_code	关键词code
     * @apiVersion 0.0.1
     * @apiSuccessExample {json} Success-Response:
     *   HTTP/1.1 201 Created
     *		return .html
     */
	public function getxcinfo(Request $req)
	{
		$value = $req->all();
		if( empty($value['object_type']) || empty($value['object_id']) )  return $this->responseFromat(4300, [], '缺少参数！');

		if( $value['object_type'] == 'n' ) {
               if( empty($value['keyword_code']) ) {
                    $keyword_code = DB::table('draft_keyword')->where(['draft_id'=>$value['object_id']])->select(['keyword_code'])->first()['keyword_code'];
               } else {
                    $keyword_code = $value['keyword_code'];
               }
			
		}
          $url = "https://www.xincheng.tv/share2/#/?draft_id=".$value['object_id']."&keyword_code=".$value['keyword_code'];
		header('location: '.$url);
	}


}