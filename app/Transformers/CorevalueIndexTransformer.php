<?php

namespace App\Transformers;

use App\Models\Corevalueindex;
use League\Fractal\TransformerAbstract;

class CorevalueIndexTransformer extends TransformerAbstract
{
    public static function transform($data)
    {
        return collect($data)->toArray();
    }
}
