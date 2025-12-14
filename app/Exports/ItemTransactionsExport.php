<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemTransactionsExport implements FromCollection, WithHeadings
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions->map(function ($t) {
            return [
                'Transaction ID' => $t->id,
                'Item'           => optional($t->item)->name,
                'Warehouse'      => optional($t->warehouse)->name,
                'User'           => optional($t->user)->name,
                'Quantity'       => $t->quantity,
                'Type'           => $t->type,
                'Date'           => $t->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Item',
            'Warehouse',
            'User',
            'Quantity',
            'Type',
            'Date',
        ];
    }
}
