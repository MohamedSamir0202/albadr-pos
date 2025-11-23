@extends('admin.layouts.app', [
    'pageName' => 'Advanced Settings',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Advanced Settings</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.advanced.update') }}" id="main-form">
                        @csrf
                        @method('Put')

                        <div class="form-group mb-3">
                            <label>Allow Decimal Quantities</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="allow_decimal_quantities" name="allow_decimal_quantities"
                                       @if($advancedSettings->allow_decimal_quantities) checked @endif>

                                <label class="form-check-label" for="allow_decimal_quantities">
                                    (Yes, allow entering 0.5 kg, 1.25 L)
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Default Discount Application Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="default_discount_method"
                                       id="discount_percentage" value="percentage"
                                       @if($advancedSettings->default_discount_method == 'percentage') checked @endif>

                                <label class="form-check-label" for="discount_percentage">
                                    Percentage (%)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="default_discount_method"
                                       id="discount_fixed" value="fixed"
                                       @if($advancedSettings->default_discount_method == 'fixed') checked @endif>

                                <label class="form-check-label" for="discount_fixed">
                                    Fixed Amount (EGP)
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Enable Available Payment/Till Methods</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]"
                                       value="cash" id="payment_cash"
                                       @if(in_array('cash', $advancedSettings->payment_methods)) checked @endif>

                                <label class="form-check-label" for="payment_cash">
                                    Cash
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]"
                                       value="deferred" id="payment_deferred"
                                       @if(in_array('deferred', $advancedSettings->payment_methods)) checked @endif>

                                <label class="form-check-label" for="payment_deferred">
                                    Deferred
                                </label>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="card-footer clearfix">
                    <x-form-submit text="Update Advanced Settings"></x-form-submit>
                </div>
            </div>
            </div>
    </div>
@endsection
