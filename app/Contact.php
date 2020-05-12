<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    protected $guarded = [];
    public function handle(EventModelCreated $event){

        $event->model->ingoing()->save(new Ingoing);
         
    }
}
