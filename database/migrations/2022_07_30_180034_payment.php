<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Payment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('tdId');
            $table->string('name');
            $table->string('ownerId');
            $table->string('payment_for');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->integer('amount');
            $table->date('date_of_payment');
            
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
        Schema::dropIfExists('payments');
    }
}
