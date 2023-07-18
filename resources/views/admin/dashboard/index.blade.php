@extends('layouts.admin')

@section('page-title')
Halaman Utama
@endsection

@section('page-active-route')
Utama
@endsection

@section('page-content-body')
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card info-card sales-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Kategori Pembayaran</h5>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $totalCategoryPayment }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card sales-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Kategori Barang</h5>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $totalCategory }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card sales-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Sub Kategori Barang</h5>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $totalSubCategory }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card sales-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Barang</h5>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $totalProperty }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card sales-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Pelanggan</h5>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $totalCustomer }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card info-card sales-card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Transaksi</h5>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $totalTransaction }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection