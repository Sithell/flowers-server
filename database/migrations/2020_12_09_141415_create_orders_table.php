<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('price')->nullable();
            $table->string('city');
            $table->string('address');
            $table->dateTime('deliver_by')->nullable();
            $table->unsignedInteger('change')->nullable();
            $table->string('payment_method');
            $table->boolean('for_yourself')->default(true);
            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->boolean('postcard')->default(false);
            $table->text('postcard_text')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone');
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
        Schema::dropIfExists('orders');
    }
}
