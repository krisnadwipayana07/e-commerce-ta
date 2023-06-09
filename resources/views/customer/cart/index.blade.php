@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container mb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Keranjang Belanja</h2>
                    </div>
                </div>
            </div>
            @php
                $total = 0;
            @endphp
            <div class="row">
                <div class="col-md-12">
                    @foreach ($carts as $cart)
                        <div class="row">
                            <div class="col-md-2">
                                {{-- 
                            <div class="align-middle">
                                <input type="checkbox" class="checkbox" id="checkbox{{ $cart->id }}" value="{{ $cart->id }}">
                            </div>
                            --}}
                            </div>
                            <div class="col-md-2">
                                <img src="{{ asset('upload/admin/property/' . $cart->property->img) }}"
                                    alt="{{ asset($cart->property->name) }}" />
                            </div>
                            <div class="col-md-6">
                                <div class="my-3">
                                    @php
                                        $total += $cart->property->price * $cart->quantity;
                                    @endphp
                                    <b>{{ $cart->property->name }}</b>
                                    <br><b style="color:black">Jumlah&emsp;: {{ $cart->quantity }}x</b>
                                    <br><b style="color:black">Harga&emsp; : {{ format_rupiah($cart->property->price) }}</b>
                                    <br>
                                    <h5><b style="color:brown">Total&emsp;:
                                            {{ format_rupiah($cart->property->price * $cart->quantity) }}</h5></b>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <form action="{{ route('customer.cart.destroy', ['cart' => $cart->id]) }}"
                                                method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </div>
                                        <div class="col-md-1">
                                            <form class="mt-2">
                                                <a href="{{ route('customer.cart.edit', ['cart' => $cart->id]) }}"
                                                    class="btn btn-warning"><i class="fa fa-solid fa-pencil"></i></a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                @if (count($carts) === 0)
                    <h3><div class="text-center fw-bold py-4">Keranjang Anda Kosong, Mohon Isi Terlebih Dahulu </div></h3>
                @else
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <!-- <b>Total: {{ format_rupiah($total) }}</b> -->
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('customer.checkout.index') }}" class="btn btn-block btn-success">Checkout</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <form action="" method="" id="form-checkout">
        @csrf
    </form>
    <!-- end news section -->
@endsection

@section('page-js')
    <script>
        $("body").on("click", "checkbox", function() {
            var checkbox = $(this);
        })
    </script>
@endsection
