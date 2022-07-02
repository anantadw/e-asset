<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('admin_name', 20);
            $table->string('item_name', 20);
            $table->string('item_category', 20);
            $table->unsignedInteger('item_stock');
            $table->enum('status', [1, 2, 3]);
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
        Schema::dropIfExists('incoming_transactions');
    }
}
