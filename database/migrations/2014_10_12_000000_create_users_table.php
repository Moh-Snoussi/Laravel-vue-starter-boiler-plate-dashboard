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
            $table->timestamp('emailVerifiedAt')->nullable();
            $table->string('password')->nullable();
            $table->integer('role')->default(1);
            $table->string('apiToken', 60)->nullable()->unique();
            $table->softDeletes();
            $table->boolean('active')->default(false);
            $table->string('activationToken')->nullable();
            $table->string('ipAddressOnRegistration')->nullable();
            $table->string('ipAddressOnLastLogin')->nullable();
            $table->string('languages')->nullable();
            $table->string('deviceOnRegistration')->nullable();
            $table->string('deviceOnLastLogin')->nullable();
            $table->string('comingFromBeforeRegistering')->nullable();
            $table->string('comingFromBeforeLastLogin')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('cardNumber')->nullable();
            $table->string('pin')->nullable();
            $table->timestamp('lastLoginDate')->nullable();
            $table->string('provider')->nullable();
            $table->string('providerId')->nullable();
            $table->string('avatar')->nullable();
            $table->string('givenName')->nullable();
            $table->string('familyName')->nullable();
            $table->string('token')->nullable();
            $table->string('refreshToken')->nullable();
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
