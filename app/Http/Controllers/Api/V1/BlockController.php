<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon; 
use App\Models\Draft;
use App\Models\DraftIndustry;
use App\Models\DraftKeyword;
use App\Models\DraftIntro;
use App\Models\DraftBody;
use App\Models\Industry; 
use App\Models\KeyWordBlock;
use App\Utils\InsideConnect;
use Illuminate\Http\Request;  

use App\Transformers\ContentTransformer; 
use App\Transformers\CorevalueIndexTransformer; 
class BlockController extends BaseController
{
	function __construct(KeyWordBlock $keywordblock,Draft $draft,DraftIndustry $draftindustry,DraftKeyword $draftkeyword,DraftIntro $draftintro,DraftBody $draftbody,Industry $industry){ 
  	  $this->draft = $draft;
      $this->draftindustry = $draftindustry;
      $this->draftkeyword = $draftkeyword;
      $this->draftintro =$draftintro;
      $this->draftbody =$draftbody;
      $this->industry =$industry;
      $this->keywordblock = $keywordblock;
		  $this->res = array(
          'status_code' => 200,
          'message'     => null,
          'data'        => null
        );
	}




}