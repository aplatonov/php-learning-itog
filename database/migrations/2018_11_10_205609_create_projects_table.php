<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name', 150);
            $table->text('description');
            $table->integer('speciality_id')->unsigned();
            $table->string('doc', 90)->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->integer('owner_id')->unsigned();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('speciality_id')->references('id')->on('speciality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_owner_id_foreign');
            $table->dropForeign('projects_category_id_foreign');
        });

        Schema::drop('projects');
    }
}
