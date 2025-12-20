@extends('admin.layouts.app', [
    'pageName' => __(' sales'),
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Sale</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.sales.store') }}" id="main-form">
                        @csrf

                        <div class="row">
                            <!-- Client -->
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="client_id">Client</label>
                                    <select name="client_id" id="client_id"
                                            class="form-control select2 @error('client_id') is-invalid @enderror">
                                        <option value="">Choose</option>
                                        @foreach($clients as $client)
                                            <option
                                                @if(old('client_id') == $client->id) selected @endif
                                                value="{{ $client->id }}">
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="sale_date">Date</label>
                                    <input type="text" class="form-control datepicker @error('sale_date') is-invalid @enderror"
                                           id="sale_date" placeholder="Date" name="sale_date">
                                    @error('sale_date')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Invoice Number -->
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="invoice_number">Invoice Number</label>
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                                           id="invoice_number" placeholder="Invoice Number" name="invoice_number">
                                    @error('invoice_number')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Safe -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="safe_id">Safe</label>
                                    <select name="safe_id" id="safe_id"
                                            class="form-control select2 @error('safe_id') is-invalid @enderror">
                                        @foreach($safes as $safe)
                                            <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('safe_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Warehouse -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="warehouse_id">Select Warehouse</label>
                                    <select id="warehouse_id" name="warehouse_id"
                                            class="form-control @error('warehouse_id') is-invalid @enderror">
                                        <option value="">-- Choose Warehouse --</option>
                                        @foreach ($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Item row -->
                        <div class="row">
                            <div class="col-sm-3">
                                <label for="item_id">Item</label>
                                <select id="item_id" class="form-control select2">
                                    <option value="">Choose</option>
                                    @foreach($items as $item)
                                        <option data-price="{{ $item->price }}"
                                                data-quantity="{{ $item->quantity }}"
                                                value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <label for="qty">Quantity</label>

                                <!-- ✅ ربط الإعداد allow_decimal_quantities -->
                                <input type="number" id="qty" class="form-control"
                                       placeholder="Quantity"
                                       step="{{ $advancedSettings->allow_decimal_quantities ? '0.01' : '1' }}"
                                       min="1">
                            </div>

                            <div class="col-sm-6">
                                <label for="notes">Notes</label>
                                <input type="text" id="notes" class="form-control" placeholder="Notes">
                            </div>

                            <div class="col-sm-1">
                                <button type="button" id="add_item"
                                        class="btn btn-primary btn-block" style="margin-top:32px;">
                                    <i class="fa fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Items table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Qnt</th>
                                    <th>Total</th>
                                    <th>Notes</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="items_list"></tbody>

                                <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th id="total_price">0</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Discount</th>
                                    <th id="discount">0</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Net</th>
                                    <th id="net">0</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Paid</th>
                                    <th id="paid">0</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Remaining</th>
                                    <th id="remaining">0</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Discount Section -->
                        <div class="discount-box">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Discount Type</label>

                                    @foreach($discountTypes as $discountTypeVal => $discountType)
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   id="discount{{$discountTypeVal}}"
                                                   type="radio" name="discount_type"
                                                   value="{{ $discountTypeVal }}"
                                                @if(
                                                    old('discount_type') == $discountTypeVal ||
                                                    ($advancedSettings->default_discount_method == 'percentage'
                                                        && $discountTypeVal == \App\Enums\DiscountTypeEnum::percentage->value
                                                    ) ||
                                                    ($advancedSettings->default_discount_method == 'fixed'
                                                        && $discountTypeVal == \App\Enums\DiscountTypeEnum::fixed->value
                                                    )
                                                ) checked @endif
                                            >
                                            <label class="form-check-label" for="discount{{$discountTypeVal}}">
                                                {{ $discountType }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="col-sm-3">
                                    <label>Discount Value</label>
                                    <input type="text" class="form-control" id="discount_value"
                                           name="discount_value" placeholder="Discount Value">
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="payment-type">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Payment Type</label>

                                    <!-- ✅ الدفع نقدي -->
                                    @if(in_array('cash', $advancedSettings->payment_methods))
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   id="payment_type_cash"
                                                   type="radio" name="payment_type"
                                                   value="{{ \App\Enums\PaymentTypeEnum::cash->value }}"

                                                   checked>
                                            <label class="form-check-label" for="payment_type_cash">
                                                {{ \App\Enums\PaymentTypeEnum::cash->label() }}
                                            </label>
                                        </div>
                                    @endif

                                    <!-- ✅ الدفع آجل -->
                                    @if(in_array('deferred', $advancedSettings->payment_methods))
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   id="payment_type_debt"
                                                   type="radio" name="payment_type"
                                                   value="{{ \App\Enums\PaymentTypeEnum::debt }}">
                                            <label class="form-check-label" for="payment_type_debt">
                                                {{ \App\Enums\PaymentTypeEnum::debt->label() }}
                                            </label>
                                        </div>
                                    @endif

                                </div>

                                @if(in_array('deferred', $advancedSettings->payment_methods))
                                <div class="col-sm-3">
                                    <label>Payment Amount</label>
                                    <input type="text" id="payment_amount" name="payment_amount"
                                           class="form-control" placeholder="Payment Amount">
                                </div>
                                @endif

                            </div>
                        </div>

                    </form>
                </div>

                <div class="card-footer clearfix">
                    <x-form-submit text="Create"></x-form-submit>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
<script>

    $(document).ready(function (){

        // ✅ منع الأرقام العشرية لو الإعداد غير مفعل
        @if(!$advancedSettings->allow_decimal_quantities)
        $("#qty").on("input", function () {
            this.value = this.value.replace(/\D/g, '');
        });
        @endif

        calculateDiscount();
    });

    var counter = 1
    var totalPrice = 0;
    var net = 0;

    $("#add_item").on('click', function (e) {
        e.preventDefault();

        let item = $("#item_id");
        let itemID = item.val();
        let selectedItem = $("#item_id option:selected");
        let itemName = selectedItem.text()
        let itemPrice = selectedItem.data('price');
        let qnt = $("#qty")
        var itemQty = qnt.val();
        let notes = $("#notes")
        let itemNotes = notes.val();

        if (!itemID) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please choose an item',
            });
            return;
        }

        let itemTotal = itemPrice * itemQty;

        $("#items_list").append(`
            <tr>
                <td>${counter}</td>
                <td>
                    <span>${itemName}</span>
                    <input type="hidden" name="items[${itemID}][id]" value="${itemID}">
                    <input type="hidden" name="items[${itemID}][name]" value="${itemName}">
                </td>
                <td>
                    ${itemPrice}
                    <input type="hidden" name="items[${itemID}][price]" value="${itemPrice}">
                </td>

                <td><input type="number" class="form-control" name="items[${itemID}][qty]" value="${itemQty}"></td>

                <td>
                    ${itemTotal}
                    <input type="hidden" name="items[${itemID}][itemTotal]" value="${itemTotal}">
                </td>

                <td>
                    ${itemNotes}
                    <input type="hidden" name="items[${itemID}][notes]" value="${itemNotes}">
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm deleteItem">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        counter++;
        totalPrice += itemTotal;
        totalPrice = Math.round((totalPrice + Number.EPSILON) * 100) / 100;
        $("#total_price").text(totalPrice);

        calculateDiscount();

        // reset inputs
        item.val("").trigger('change')
        qnt.val("")
        notes.val("")
    });


    $(document).on('click', '.deleteItem', function (e) {
        let itemTotal = $(this).closest('tr').find('td:nth-child(5)').text();
        totalPrice -= itemTotal;
        totalPrice = Math.round((totalPrice + Number.EPSILON) * 100) / 100;
        $("#total_price").text(totalPrice);
        calculateDiscount();
        $(this).closest('tr').remove();
    });


    $("#discount_value").on('keyup', function (){
        calculateDiscount();
    });

    $('input[name="discount_type"]').on('change', function (){
        calculateDiscount();
    });

    function calculateDiscount(){
        let discount = 0;
        let discountType = $('input[name="discount_type"]:checked').val();

        if (discountType == '{{ \App\Enums\DiscountTypeEnum::fixed->value }}') {
            discount = parseFloat($("#discount_value").val() || 0);
        } else {
            let discountPercent = parseFloat($("#discount_value").val() || 0);
            discount = (totalPrice * discountPercent) / 100;
        }

        discount = Math.round((discount + Number.EPSILON) * 100) / 100;

        net = totalPrice - discount;
        net = Math.round((net + Number.EPSILON) * 100) / 100;

        $("#discount").text(discount);
        $("#net").text(net);

        calculateRemaining();
    }


    $('input[name="payment_type"]').on('change', function (){
        let paymentType = $('input[name="payment_type"]:checked').val();

        if(paymentType == 1){
            $("#payment_amount").val("").attr('disabled', true);
        }else{
            $("#payment_amount").attr('disabled', false);
        }

        calculateRemaining();
    });

    $("#payment_amount").on('keyup', function (){
        calculateRemaining();
    });

    function calculateRemaining(){
        let paymentType = $('input[name="payment_type"]:checked').val();
        let paid = parseFloat($("#payment_amount").val());

        if(paymentType == 1){
            paid = net;
        }

        let remaining = net - paid;
        remaining = Math.round((remaining + Number.EPSILON) * 100) / 100;

        $("#paid").text(paid);
        $("#remaining").text(remaining);
    }

</script>
@endpush
