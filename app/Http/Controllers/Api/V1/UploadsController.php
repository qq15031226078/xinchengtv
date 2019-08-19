<?php

namespace App\Http\Controllers\Api\V1;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;  
use Illuminate\Http\Request; 

class UploadsController extends BaseController
{

	public function __construct() {
        $this->res = array(
                'status_code'=> 200,
                'message'    => null,
                'data'       =>null
            );    
		$this->auth = new Auth(env('QINIU_accessKey'), env('QINIU_secretKey')); 
        $this->token = $this->auth->uploadToken(env('QINIU_bucketName')); 
    }

	public function UploadFile(Request $req){ 
		 $array_url = array();
		 try {
 				 $allowedExts = array("image/gif", "image/jpeg", "image/jpg", "image/png");
 				 $upManager = new UploadManager();
 				 if($req->hasFile('file')){
 				 	$files = $req->file('file');
 				 	foreach ($files as $key => $value) {  
 				 		if(!in_array($value->getMimeType(),$allowedExts)){
                            $this->res['status_code'] =201;
                            $this->res['errors'] = '图片类型不匹配！';
                            return  $this->response->array($this->res)->setStatusCode($this->res['status_code']); 
                    	}
	                    $exts     = explode('/', $value->getMimeType());
	            		$fileName = 'xincheng-img-'.str_random(10).'.'.$exts[1];   
 				 	 	$ret      =  $upManager->putFile($this->token, $fileName, $_FILES[ 'file' ][ 'tmp_name' ][$key]);  
 				 	 	$array_url[$key] =  'http://'.env('QINIU__DOMAIN').'/'.$fileName; 
 				 	}
 				 	$this->res['data'] = $array_url; 
                	return	$this->response->array($this->res)->setStatusCode($this->res['status_code']);
 				 }

		 }catch(Exception $e) {
 			Log::error('UploadImg bad.'. $e); 
	        return false;
        } 
	}

}