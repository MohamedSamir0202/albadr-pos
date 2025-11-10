<?php

use App\Models\Client;
use App\Models\User;
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
        Schema::create('client_account_transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Client::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->decimal('credit', 10, 2);
            $table->decimal('debit', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->nullableMorphs('reference');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_account_transactions');
    }
};
