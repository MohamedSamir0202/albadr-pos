<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // Items
        Schema::table('items', function(Blueprint $table) {
            $table->foreign('unit_id')->references('id')->on('units')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('category_id')->references('id')->on('categories')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        // Sales
        Schema::table('sales', function(Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });

        // Orders
        Schema::table('orders', function(Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('sale_id')->references('id')->on('sales')
                ->nullOnDelete()
                ->nullOnUpdate();
        });

        // Order Items
        Schema::table('order_items', function(Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')
                ->cascadeOnDelete();
            $table->foreign('item_id')->references('id')->on('items')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function(Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['item_id']);
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['sale_id']);
        });

        Schema::table('sales', function(Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('items', function(Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['category_id']);
        });
    }
};
