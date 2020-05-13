<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $guarded = [];

    public function profile() {
        return $this->belongsTo('App\Profile', 'profile_id');
      }
}
