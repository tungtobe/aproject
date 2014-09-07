<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	public function up()
{
    Schema::create('users', function($table)
    {
        $table->increments('id');
        $table->string('email');
        $table->string('username');
        $table->string('type');
        $table->string('access_token');
        $table->string('remember_token',100)->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::drop('users');
}

}
