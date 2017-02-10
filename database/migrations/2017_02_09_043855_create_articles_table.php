<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("articles", function (Blueprint $table) {
            $table->integer("user_id")->index();
            $table->string("link")->primary();
            $table->string("title", 255);
            $table->string("short_description", 255);
            $table->string("content");
            $table->string("thumbnail", 255);
            $table->tinyInteger("status");
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
        Schema::drop("articles");
    }
}
