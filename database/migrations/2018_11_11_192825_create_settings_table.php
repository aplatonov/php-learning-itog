<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('settings_name', 150)->nullable()->default(null);
            $table->text('how_it_works_1')->nullable()->default(null);
            $table->text('how_it_works_2')->nullable()->default(null);
            $table->text('how_contact_us')->nullable()->default(null);
            $table->string('address', 150)->nullable()->default(null);
            $table->string('phone', 150)->nullable()->default(null);
            $table->string('email', 150)->nullable()->default(null);
            $table->string('carousel_pics', 190)->nullable()->default(null);
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
        Schema::drop('settings');
    }
}
