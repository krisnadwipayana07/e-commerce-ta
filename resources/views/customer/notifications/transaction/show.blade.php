@extends('landing.landing')

@section('sidebar')
@include('landing.sidebar')
@endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2>{{ $transaction->code }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($detail_transactions as $item)
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('upload/admin/property/' . $item->property->img) }}"  alt="{{ asset($item->property->name) }}" />
                        </div>
                        <div class="col-md-8">
                            <div class="text-center">
                                <h5>Property Description</h5>
                            </div>
                            <form>
                                <fieldset disabled>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Property Name</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $item->property->name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Price</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ format_rupiah($item->property->price) }}">
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-8">
                            <div class="text-center">
                                <h5>Payment Description</h5>
                            </div>
                            <form>
                                <fieldset disabled>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Payment Method</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction->category_payment->name }}">
                                    </div>
                                    @if ($isCredit)
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Credit Periode</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction->credit_period }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Credit Payment</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ format_rupiah($transaction->payment_credit) }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="disabledTextInput" class="form-label">Remaining Payment</label>
                                                <input type="text" id="disabledTextInput" class="form-control" value="{{ format_rupiah($transaction->total_payment - ($transaction->payment_credit * $count_submission_credit_payment))  }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="disabledTextInput" class="form-label">Remaining Instalment</label>
                                                <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction->credit_period - $transaction->total_phase  }}x">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-block">{{ $transaction->status }}</button>
                        </div>
                    </div>
                    @if ($isCredit)
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <a href="javascript:;" onclick="penuliskode_modal('Pay a Credit', '{{ route("customer.credit.payment.index", ["transaction" => $transaction->id]) }}')" class="btn btn-block btn-warning">Pay</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
