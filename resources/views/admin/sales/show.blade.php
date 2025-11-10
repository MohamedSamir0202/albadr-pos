@extends('admin.layouts.app', ['pageName' => 'Invoice Details'])

@section('content')
<div class="container py-4">

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Invoice #{{ $sale->invoice_number }}</h5>
            <a href="{{ route('admin.sales.download', $sale->id) }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Download PDF
            </a>

        </div>

        <div class="card-body" id="invoice-section">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-secondary mb-2">Client Information</h6>
                    <p class="mb-1"><strong>Name:</strong> {{ $sale->client->name ?? '-' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $sale->client->phone ?? '-' }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $sale->client->address ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6 class="text-secondary mb-2">Invoice Info</h6>
                    <p class="mb-1"><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i') }}</p>
                    <p class="mb-1"><strong>Safe:</strong> {{ $sale->safe->name ?? '-' }}</p>
                    <p class="mb-1"><strong>Warehouse:</strong> {{ $sale->warehouse->name ?? '-' }}</p>
                    <p class="mb-0"><strong>User:</strong> {{ $sale->user->username ?? '-' }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-center">{{ $item->pivot->quantity }}</td>
                                <td class="text-end">{{ number_format($item->pivot->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format($item->pivot->total_price, 2) }}</td>
                                <td>{{ $item->pivot->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td class="text-end fw-bold">{{ number_format($sale->total, 2) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Discount:</td>
                            <td class="text-end fw-bold">{{ number_format($sale->discount, 2) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Net Amount:</td>
                            <td class="text-end fw-bold text-primary">{{ number_format($sale->net_amount, 2) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Paid:</td>
                            <td class="text-end fw-bold text-success">{{ number_format($sale->paid_amount, 2) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Remaining:</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($sale->remaining_amount, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-3 text-center text-muted small">
                Printed at: {{ now()->format('Y-m-d H:i') }}
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function printInvoice() {
    const printContents = document.getElementById('invoice-section').innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
@endpush

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-section, #invoice-section * {
        visibility: visible;
    }
    #invoice-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endpush
