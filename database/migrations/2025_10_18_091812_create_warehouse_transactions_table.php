<?php

use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyInteger('transaction_type'); // WarehouseTransactionTypeEnum
            $table->decimal('quantity', 10, 2);
            $table->decimal('quantity_after', 10, 2);
            $table->nullableMorphs('reference');
            $table->text('description');
            //$table->foreignIdFor(Item::class)->constrained();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_transactions');
    }
};
