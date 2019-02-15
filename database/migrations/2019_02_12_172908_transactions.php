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
        Schema::create('transactions', function (Blueprint $table){
        $table->increments('transaction_id');
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->string('sender_cardNumber');
            $table->string('receiver_cardNumber');
            $table->integer('amount');
            $table->string('reference');
            $table->boolean('success');
            $table->timestamps();
       
        }
    );
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