<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('cardNumber');
            $table->string('userId');
            $table->string('name');
            $table->string('currentBalance');
            $table->string('operation');
            $table->string('secondPartyCardNumber')->nullable();
            $table->string('secondPartyName')->nullable();
            $table->string('amount');
            $table->string('reference')->nullable();
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('accounts');
    }
}