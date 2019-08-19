<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;  
use JPush\Client as JPush;

class PushController extends BaseController
{
	function __construct(){
		$this->client = new JPush('528a4e098619780736f18830', '9a646710863e7f185c7546e8');
		//$client->setPlatform()
	}

	public function pushTest(Request $req){
		$pusher = $this->client->push();
		$pusher->setPlatform(['android']); 
		$pusher->addRegistrationId('140fe1da9e9f530a1f1');
		$pusher->androidNotification('Hello');

			try { 
			    $pusher->send();
			} catch (\JPush\Exceptions\JPushException $e) { 
			  //  print $e;
			}
	}
}