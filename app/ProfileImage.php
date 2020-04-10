<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    protected $fillable = ['profile_id', 'profile_image_path'];

    public function profile() {
        return $this->belongsTo('App\Profile');
    }

}
