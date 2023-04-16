@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Edit Quantity Checkout</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 margin_top40">
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2">
                            <img src="{{ asset('upload/admin/property/' . $cart->property->img) }}"
                                alt="{{ asset($cart->property->name) }}" />
                        </div>
                        <div class="col-md-6">
                            <div class="my-3">
                                <b>{{ $cart->property->name }}</b>
                                <br><b style="color:black">Price: {{ format_rupiah($cart->property->price) }}</b>
                                <form action="{{ route('customer.checkout.update.quantity', ['cart' => $cart->id]) }}" method="POST" class="mt-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" min="1" max="{{ $cart->property->stock }}" class="form-control" value="{{ $cart->quantity }}" >
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end news section -->
@endsection