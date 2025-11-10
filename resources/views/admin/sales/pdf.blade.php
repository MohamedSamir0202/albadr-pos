<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $sale->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #f5f5f5; }
        h2, h4 { margin: 5px 0; text-align: center; }
        .text-end { text-align: right; }
        .summary { margin-top: 20px; float: right; width: 40%; }
        .summary td { border: none; }
    </style>
</head>
<body>
    <h2>Invoice #{{ $sale->invoice_number }}</h2>
    <h4>Date: {{ $sale->created_at->format('Y-m-d H:i') }}</h4>

    <p><strong>Client:</strong> {{ $sale->client->name }}</p>
    <p><strong>Safe:</strong> {{ $sale->safe->name ?? '-' }}</p>
    <p><strong>Created By:</strong> {{ $sale->user->username ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['unit_price'], 2) }}</td>
                    <td>{{ number_format($item['total_price'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr><td><strong>Total:</strong></td><td>{{ number_format($sale->total, 2) }}</td></tr>
        <tr><td><strong>Discount:</strong></td><td>{{ number_format($sale->discount, 2) }}</td></tr>
        <tr><td><strong>Net Amount:</strong></td><td>{{ number_format($sale->net_amount, 2) }}</td></tr>
        <tr><td><strong>Paid:</strong></td><td>{{ number_format($sale->paid_amount, 2) }}</td></tr>
        <tr><td><strong>Remaining:</strong></td><td>{{ number_format($sale->remaining_amount, 2) }}</td></tr>
    </table>
</body>
</html>
