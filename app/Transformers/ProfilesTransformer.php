<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Profile;

class ProfilesTransformer extends TransformerAbstract
{
    public function transform(Profile $profile)
    {
        return [
            'id' => $profile->id,
            'name' => $profile->name,
            'image' => $profile->image,
        ];
    }
}
