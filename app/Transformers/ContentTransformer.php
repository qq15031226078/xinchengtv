<?php

namespace App\Transformers;

use App\Models\XcContent;
use League\Fractal\TransformerAbstract;

class ContentTransformer extends TransformerAbstract
{
    public function transform(XcContent $content)
    {
        return $content->attributesToArray();
    }
}