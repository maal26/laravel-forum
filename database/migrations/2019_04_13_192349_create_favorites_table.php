<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->morphs('favorited');
            $table->timestamps();

            $table->unique(['user_id', 'favorited_id', 'favorited_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
