<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    public function options() {
        return $this->hasMany('App\SelectOption');
    }
}
