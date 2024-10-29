<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOrderIdFromProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['order_id']); // Drop foreign key constraint
            
            // Then drop the order_id column
            $table->dropColumn('order_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add the order_id column back
            $table->unsignedBigInteger('order_id')->nullable();

            // Recreate the foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
}