@extends('landing.landing')

@section('sidebar')
@include('landing.sidebar')
@endsection

@section('content')
<div class="container">
    <div class="d-flex align-items-center my-3">
        <div class="font-weight-bold">
            Status : 
        </div>
        <div class="gap-5">
        <a href="{{ route('customer.notification.transaction.index') }}" class="btn btn-{{ request()->get('filter') === null ? 'primary' : 'outline-primary' }} rounded-pill ml-1">Semua</a>
        <a href="{{ route('customer.notification.transaction.index', ['filter' => 'payment']) }}" class="btn btn-{{ request()->get('filter') === "payment" ? 'primary' : 'outline-primary' }} rounded-pill ">Pembayaran</a>
        <a href="{{ route('customer.notification.transaction.index', ['filter' => 'paid']) }}" class="btn btn-{{ request()->get('filter') === "paid" ? 'primary' : 'outline-primary' }} rounded-pill ">Lunas</a>
        <a href="{{ route('customer.notification.transaction.index', ['filter' => 'rejected']) }}" class="btn btn-{{ request()->get('filter') === "rejected" ? 'primary' : 'outline-primary' }} rounded-pill ">Ditolak</a>
        <a href="{{ route('customer.notification.transaction.index', ['filter' => 'in_transit']) }}" class="btn btn-{{ request()->get('filter') === "in_transit" ? 'primary' : 'outline-primary' }} rounded-pill ">Diproses Pengiriman</a>
        <a href="{{ route('customer.notification.transaction.index', ['filter' => 'delivered']) }}" class="btn btn-{{ request()->get('filter') === "delivered" ? 'primary' : 'outline-primary' }} rounded-pill ">Sampai Ditujan</a>
        </div>
    </div>
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
                                <h5>Deskripsi Barang</h5>
                            </div>
                            <form>
                                <fieldset disabled>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Nama Barang</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $item['property'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Harga</label>
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
                                <div class="card-header">Akun Pembayaran</div>
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
                                <h5>Deskripsi Pembayaran</h5>
                            </div>
                            <form>
                                <fieldset disabled>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Metode Pembayaran</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['payment'] }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Total Pembayaran</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['total_payment'] }}">
                                    </div>
                                    @if($transaction['isTransfer'])
                                    <div class="mb-3">
                                        <label for="disabledTextInput" class="form-label">Batas Waktu Pembayaran</label>
                                        <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['due_date'] }}">
                                    </div>
                                    @endif
                                    @if ($transaction['isCredit'])
                                        <div class="mb-3">
                                            <label for="disabledTextInput" class="form-label">Jumlah Kredit</label>
                                            <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['credit_period'] }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="disabledTextInput" class="form-label">DP (Uang Muka)</label>
                                            <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['down_payment'] }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="disabledTextInput" class="form-label">Pembayaran Per Bulan</label>
                                            <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['credit_payment'] }}">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="disabledTextInput" class="form-label">Sisa Pembayaran</label>
                                                    <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['remaining_payment']  }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="disabledTextInput" class="form-label">Sisa Cicilan</label>
                                                    <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['remaining_instalment']  }}x">
                                                </div>
                                            </div>
                                        </div>
                                        @if ($transaction['statuses'] == "in_progress")
                                        <div class="mb-3">
                                            <label for="disabledTextInput" class="form-label">Batas Pembayaran {{$transaction['isDP'] == "0"? "Uang Muka" : "Cicilan" }}</label>
                                            <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['due_date'] }}">
                                        </div>
                                        @if ($transaction['isDP'])
                                        <div class="mb-3">
                                            <label for="disabledTextInput" class="form-label">Denda (melebihi tenggat bayar cililan, terhitung 2% dari total)</label>
                                            <input type="text" id="disabledTextInput" class="form-control" value="{{ $transaction['overprice'] }}">
                                        </div>
                                        @endif
                                        @endif
                                    @endif

                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            @if ($transaction['delivery'])
                                <h5>Status Pengiriman - {{$transaction['delivery']}} </h5>
                            @endif
                            @if ($transaction["message"])
                                <h5> Note : </h5>
                                    @foreach ($transaction["message"] as $item)
                                        <p class="text-danger"> * {{$item['message']}} </p>
                                    @endforeach
                            @endif
                            <div class="text-center">
                                <h5>Status Transaksi</h5>
                            </div>
                            {{-- {{$transaction['delivery']}} --}}
                            @if ($transaction['isCOD'] && $transaction['delivery'] == "Diterima")
                            <div>
                                <button type="button" class="btn btn-success text-white btn-block text-uppercase" style=" font-weight: 600"> SUDAH DIKIRIM </button>
                            </div>
                            @else
                                <div>
                                    <button type="button" class="btn btn-{{ $transaction['button']  }} {{$transaction['button'] === "success" && "text-white"}} btn-block text-uppercase" style=" font-weight: 600">{{$transaction['isDP'] && $transaction['remaining_instalment'] > 0 ? "BAYAR CICILAN PERBULAN": $transaction['status']}} </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if ($transaction['isCredit'] && $transaction['remaining_instalment'] > 0)
                    <div class="row mt-2">
                        @if ($transaction['isDP'])
                        <div class="col-md-4 mt-1"></div>
                        <div class="col-md-8 mt-1">
                            <a href="javascript:;" onclick="penuliskode_modal('Pembayaran Kredit', '{{ $transaction["route"] }}')" class="btn btn-block btn-success">Bayar Cicilan</a>
                        </div>
                        @else
                            @if ($transaction['statuses'] == "in_progress")
                                <div class="col-md-4"></div>
                                <div class="col-md-8 ">
                                    <a href="javascript:;" onclick="penuliskode_modal('Bayar Uang Muka', '{{ $transaction["routeDP"] }}')" class="btn btn-block btn-success @if($transaction['remaining_instalment'] == 0) disabled @endif">Bayar Uang Muka</a>
                                </div>
                            @elseif ($transaction['statuses'] != "reject")
                                <div class="col-md-4"></div>
                                <div class="col-md-8 pb-1">
                                    <a href="{{ route('customer.notification.transaction.edit', $transaction['id']) }}" class="btn btn-block btn-secondary">UBAH DATA</a>
                                </div>
                            @endif
                        @endif
                    </div>
                    @elseif ($transaction['isTransfer'] && $transaction['statuses'] !== "paid" && $transaction['statuses'] !== "reject")
                    <div class="row mt-2">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <a href="javascript:;" onclick="penuliskode_modal('Bayar Transfer', '{{ $transaction["routeTransfer"] }}')" class="btn btn-block btn-success @if($transaction['status'] == 'PAID') disabled @endif">Bayar</a>
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