<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->comment('html input type');
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('slug')->unique();
            $table->boolean('is_updatable')->default(false);
            $table->double('min')->nullable();
            $table->double('max')->nullable();
            $table->double('step')->nullable();
            $table->boolean('is_unique')->default(false);;
            $table->string('default')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
