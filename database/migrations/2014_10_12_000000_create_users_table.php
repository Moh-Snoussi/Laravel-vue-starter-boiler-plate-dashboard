<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Get as much information from the user as possible.
     * this will create a table in the database with name user.
     * 
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();;
            $table->string('facebook_id')->nullable();;
            $table->string('github_id')->nullable();;
            $table->integer('role')->default(1);
            $table->string('api_token', 60)->nullable()->unique();
            $table->softDeletes();
            $table->boolean('active')->default(false);
            $table->string('activation_token')->nullable();
            $table->string('ip_adress_on_registration')->nullable();;
            $table->string('languages')->nullable();
            $table->string('device')->nullable();;
            $table->string('comming_from_before_registring')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('cardNumber')->nullable();
            $table->string('pin')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('ip_adress_on_last_login')->nullable();
            $table->string('provider')->nullable();
            $table->string('avatar')->nullable();
            $table->string('givenName')->nullable();
            $table->string('familyName')->nullable();
            $table->string('token')->nullable();
            $table->string('refrech_token')->nullable();
            $table->index('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
