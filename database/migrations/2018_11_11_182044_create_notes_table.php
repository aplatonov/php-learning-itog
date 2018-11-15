<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('note_name', 150)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->integer('to_user_id')->unsigned()->nullable();
            $table->integer('from_user_id')->unsigned()->nullable();
            $table->integer('note_category_id')->unsigned();
            $table->boolean('active')->default(true);
            $table->string('link', 190)->nullable()->default(null);
            $table->timestamps();
        });


        Schema::table('notes', function (Blueprint $table) {
            $table->foreign('to_user_id')->references('id')->on('users');
            $table->foreign('from_user_id')->references('id')->on('users');
            $table->foreign('note_category_id')->references('id')->on('notes_category');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign('notes_to_user_id_foreign');
            $table->dropForeign('notes_from_user_id_foreign');
            $table->dropForeign('notes_note_category_id_foreign');
        });

        Schema::drop('notes');
    }
}
