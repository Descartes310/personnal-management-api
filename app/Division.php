<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $guarded = [];

    public function division() {
        return $this->belongsTo('App\Division','id','parent_id');
    }
}  

