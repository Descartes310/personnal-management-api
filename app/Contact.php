<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    public function handle(EventModelCreated $event){
        $event->model->ingoing()->save(new Ingoing);
    }
}
