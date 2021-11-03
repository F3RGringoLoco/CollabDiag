<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNivel2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nivel2s', function (Blueprint $table) {
            //$table->id();
            $table->char('title_slug',30)->primary();
            $table->char('title',20);
            $table->unsignedInteger('user_id');
            $table->char('author_name', 20);
            $table->json('json_data')->nullable();
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
        Schema::dropIfExists('nivel2s');
    }
}
