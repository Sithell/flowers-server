<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostcardToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('receiver_name')->after('address');
            $table->string('receiver_phone');
            $table->string('sender_name')->nullable();
            $table->boolean('postcard')->after('contact_phone');
            $table->text('postcard_text')->nullable();
            $table->string('city');
            $table->boolean('for_yourself');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->removeColumn('receiver_name');
            $table->removeColumn('receiver_phone');
            $table->removeColumn('sender_name');
            $table->removeColumn('postcard');
            $table->removeColumn('postcard_text');
            $table->removeColumn('city');
            $table->removeColumn('for_yourself');
        });
    }
}
