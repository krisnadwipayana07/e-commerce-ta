@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-2">
                    <a href="{{ route('landing.index') }}" class="btn btn-secondary btn-block">
                        <i class="ri-arrow-left-circle-line"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>{{ $property->sub_category_property->name }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 margin_top40">
                    <div class="row d_flex">
                        <div class="col-md-5">
                            <div class="news_img">
                                <figure><img src="{{ asset('upload/admin/property/' . $property->img) }}"
                                        alt="{{ asset($property->name) }}" /></figure>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="news_text">
                                        <h3>{{ $property->name }}</h3>
                                        <span>{{ $property->sub_category_property->category_property->name }}</span>
                                        <p>
                                            {{ $property->description }}
                                            <br><b style="color:brown">{{ format_rupiah($property->price) }}</b>
                                            <br><b style="color:black">Stok {{ $property->stock }}</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if (auth()->guard('customer')->check())
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <form action="#">
                                                    @if ($property->stock > 0)
                                                        <input type="number" class="form-control" id="quantity_detail"
                                                            min="1" max="{{ $property->stock }}" value="1">
                                                    @else
                                                        <b style="color:red">(Habis)</b>
                                                    @endif
                                                </form>
                                            </div>
                                            <div class="col-md-4">
                                                <form action="{{ route('customer.cart.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                                                    <input type="hidden" name="quantity" value="1" id="quantity_cart">
                                                    <button type="submit"
                                                        class="btn btn-outline-success @if ($property->stock < 1) disabled @endif">Masukkan Keranjang</button>
                                                </form>
                                            </div>
                                            <div class="col-md-4">
                                                <form action="{{ route('customer.checkout.single') }}" method="GET">
                                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                                                    <input type="hidden" name="quantity" value="1"
                                                        id="quantity_checkout">
                                                    <button type="submit"
                                                        class="btn btn-success @if ($property->stock < 1) disabled @endif"
                                                        id="single_checkout">Checkout</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end news section -->
@endsection
