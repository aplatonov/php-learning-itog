<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_mark', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->date('finish_date')->nullable();
            $table->boolean('is_done')->default(false);
            $table->timestamps();
        });

        Schema::table('project_mark', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_mark', function (Blueprint $table) {
            $table->dropForeign('projects_project_id_foreign');
        });

        Schema::drop('project_mark');
    }
}
