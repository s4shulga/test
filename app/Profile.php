<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 *
 * @package App
 */
class Profile extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profile_images(){
        return $this->hasMany('App\ProfileImage');
    }
}
