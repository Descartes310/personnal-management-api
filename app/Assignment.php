<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function assignmentType(){
        return $this->belongsTo('App\AssignmentType');
    }
}
