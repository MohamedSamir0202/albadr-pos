<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientAccountTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientAccountService
{
    /**
     * أضف عملية مالية جديدة في حساب العميل (سحب أو إيداع)
     */
    public function recordTransaction(Client $client, float $credit, float $debit, string $description, ?Model $reference = null): ClientAccountTransaction
    {
        return DB::transaction(function () use ($client, $credit, $debit, $description, $reference) {
            $balanceChange = $credit - $debit;

            // تحديث الرصيد في جدول العملاء
            $client->increment('balance', $balanceChange);

            // إنشاء المعاملة الجديدة
            return $client->accountTransactions()->create([
                'user_id' => Auth::id(),
                'client_id' => $client->id,
                'credit' => $credit,
                'debit' => $debit,
                'balance' => $balanceChange,
                'balance_after' => $client->fresh()->balance,
                'description' => $description,
                'reference_id' => $reference?->id,
                'reference_type' => $reference ? get_class($reference) : null,
            ]);
        });
    }

    /**
     * جلب كل المعاملات الخاصة بالعميل مع الفلاتر
     */
    public function getTransactions(Client $client, $filters = [])
    {
        return $client->accountTransactions()
            ->with('user')
            ->when(!empty($filters['from_date']), fn($q) => $q->whereDate('created_at', '>=', $filters['from_date']))
            ->when(!empty($filters['to_date']), fn($q) => $q->whereDate('created_at', '<=', $filters['to_date']))
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * حساب إجماليات الرصيد والعمليات
     */
    public function calculateTotals($transactions): array
    {
        $totalCredit = $transactions->sum('credit');
        $totalDebit = $transactions->sum('debit');
        $finalBalance = $transactions->last()->balance_after ?? 0;

        return compact('totalCredit', 'totalDebit', 'finalBalance');
    }
}
