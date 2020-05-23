<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function assignmentType() {
        return $this->belongsTo(AssignmentType::class);
    }
}
