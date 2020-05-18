<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatDiscussion extends Model
{
    protected $guarded = [];

    public function messages() {
        return $this->hasMany('App\ChatMessage', 'discussion_id', 'id');
    }
}
