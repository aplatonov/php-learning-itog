<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login', 30)->unique();
            $table->string('email', 120)->unique();
            $table->string('password', 60);
            $table->string('name', 80);
            $table->string('contact_person', 190);
            $table->string('phone', 60);
            $table->integer('role_id')->unsigned()->default(2);
            $table->boolean('valid')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->string('confirmation_code')->nullable();
            $table->string('portfolio', 100)->nullable();
            $table->string('logo', 100)->nullable();
            $table->string('www', 150)->nullable();
            $table->text('description')->nullable()->default(null);
            $table->datetime('prev_login')->nullable()->default(null);
            $table->datetime('last_login')->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')
                        ->onDelete('restrict')
                        ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
        });

        Schema::drop('users');
    }
}
