<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales->map(function ($sale) {
            return [
                'Sale ID'         => $sale->id,
                'Client'          => optional($sale->client)->name,
                'Order Date'      => $sale->order_date,
                'Total Amount'    => $sale->total_amount,
                'Net Amount'      => $sale->net_amount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sale ID',
            'Client',
            'Order Date',
            'Total Amount',
            'Net Amount',
        ];
    }
}
