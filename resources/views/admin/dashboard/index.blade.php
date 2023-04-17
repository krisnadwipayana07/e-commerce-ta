@extends('layouts.admin')

@section('page-title')
    Dashboard Page
@endsection

@section('page-parent-route')
    Dashboard
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-content-body')
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card info-card sales-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Category Payment</h5>
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
                    <h5 class="card-title">Category Property</h5>
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
                    <h5 class="card-title">Sub Category Property</h5>
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
                    <h5 class="card-title">Property</h5>
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
                    <h5 class="card-title">Customer</h5>
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
                    <h5 class="card-title">Transaction</h5>
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
