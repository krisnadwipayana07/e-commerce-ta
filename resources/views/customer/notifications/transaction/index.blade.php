@extends('landing.landing')

@section('sidebar')
@include('landing.sidebar')
@endsection

@section('content')
<div class="container mt-5">
    @foreach ($transactions as $transaction)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2>{{ $transaction["code"] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($transaction["detail"] as $item)
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('upload/admin/property/' . $item['img']) }}"  alt="{{ asset($item['property']) }}" />
                        </div>
                        <div class="col-md-8">
                            <div class="text-center">
                                <h5>Property Description</h5>
                            </div>
                            <form>
                                <fieldset disabled>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Property Name</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $item['property'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Price</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $item['price'] }}">
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-4">
                            @if ($transaction['isCredit'])
                            <div class="card border-primary mb-3">
                                <div class="card-header">Payment Account</div>
                                <div class="card-body text-primary">
                                    <ul class="list-group">
                                        @foreach ($payments as $pay)
                                        @if ($pay->name != 'Cash On Delivery' && $pay->name != 'Cash' && $pay->name != 'Credit' && $pay->name != 'Kredit')
                                        <li class="list-group-item">{{ $pay->name }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="text-center">
                                <h5>Payment Description</h5>
                            </div>
                            <form>
                                <fieldset disabled>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Payment Method</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['payment'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Total Payment</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['total_payment'] }}">
                                    </div>
                                    @if($transaction['isTransfer'])
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Due Date Transfer</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['due_date'] }}">
                                    </div>
                                    @endif
                                    @if ($transaction['isCredit'])
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Credit Periode</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['credit_period'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Down Payment</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['down_payment'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Payment Per Month</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['credit_payment'] }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="disabledTextInput" class="form-label">Remaining Payment</label>
                                                <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['remaining_payment']  }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="disabledTextInput" class="form-label">Remaining Instalment</label>
                                                <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['remaining_instalment']  }}x">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                        
                        </div>
                        <div class="col-md-8">
                            <div class="text-center">
                                <h5>Status Transaction</h5>
                            </div>
                            <div>
                                <button type="button" class="btn btn-{{ $transaction['button'] }} btn-block">{{ $transaction['status'] }}</button>
                            </div>
                        </div>
                    </div>
                    @if ($transaction['isCredit'])
                    <div class="row mt-2">
                        @if ($transaction['isDP'])
                        <div class="col-md-12">
                            <a href="javascript:;" onclick="penuliskode_modal('Pay a Credit', '{{ $transaction["route"] }}')" class="btn btn-block btn-success @if($transaction['remaining_instalment'] == 0) disabled @endif">Pay</a>
                        </div>
                        @else
                            @if ($transaction['status'] == "REJECTED" || $transaction['status'] == "PENDING")
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <a href="{{ route('customer.notification.transaction.edit', $transaction['id']) }}" class="btn btn-block btn-success">Review Submission</a>
                            </div>
                            @else
                            <div class="col-md-12">
                                <a href="javascript:;" onclick="penuliskode_modal('Pay a Down Payment', '{{ $transaction["routeDP"] }}')" class="btn btn-block btn-success @if($transaction['remaining_instalment'] == 0) disabled @endif">Pay a Down Payment</a>
                            </div>
                            @endif
                        @endif
                    </div>
                    @elseif ($transaction['isTransfer'])
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <a href="javascript:;" onclick="penuliskode_modal('Pay a Transfer', '{{ $transaction["routeTransfer"] }}')" class="btn btn-block btn-success @if($transaction['status'] == 'PAID') disabled @endif">Transfer</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
