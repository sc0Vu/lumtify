<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("categories", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("parent_id")->nullable();
            $table->integer("children_id")->nullable();
            $table->string("slug", 50)->unique();
            $table->string("name", 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("categories");
    }
}
