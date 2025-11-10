<?php

namespace App\Services;

use App\Enums\SafeTransactionTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SafeService
{
    public function inTransaction(Model $reference, float $amount, string $description): void
    {
        $this->performTransaction($reference, $amount, SafeTransactionTypeEnum::in, $description);
    }

    public function outTransaction(Model $reference, float $amount, string $description): void
    {
        $this->performTransaction($reference, $amount, SafeTransactionTypeEnum::out, $description);
    }

    private function performTransaction(Model $reference, float $amount, SafeTransactionTypeEnum $type, string $description): void
    {
        if ($amount <= 0) {
            return;
        }

        DB::transaction(function () use ($reference, $amount, $type, $description) {
            $safe = $reference->safe()->lockForUpdate()->firstOrFail();

            if ($type === SafeTransactionTypeEnum::in) {
                $safe->balance += $amount;
            } else {
                $safe->balance -= $amount;
            }

            $safe->save();

            $reference->safeTransactions()->create([
                'user_id' => Auth::id(),
                'type' => $type,
                'safe_id' => $safe->id,
                'amount' => $amount,
                'balance_after' => $safe->fresh()->balance,
                'description' => $description,
            ]);
        });
    }
}
