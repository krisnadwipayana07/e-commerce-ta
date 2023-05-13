@extends('landing.landing')

@section('content')
    <section class="banner_main">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="text-bg">
                        <h1> <span class="blodark"> Bali Artha Jaya </h1>
                        <p>Tempat Beli Barang Murah Dan Berkualitas Hanya Di BAJ</p>
                    </div>
                </div>
                {{--
                <div class="col-md-4">
                    <div class="ban_img">
                        <figure><img src="images/iccon.png" alt="#" /></figure>
                    </div>
                </div>
                --}}
            </div>
        </div>
    </section>
    <div id="project" class="project">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage product">
                        <h2>List Produk</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="product_main ">
                    @foreach ($property as $item)
                        <div class="project_box">
                            <div class="dark_white_bg">
                                <img src="{{ asset('upload/admin/property/' . $item->img) }}"
                                    alt="{{ asset($item->name) }}" />
                            </div>
                            <h3>
                                <a href="{{ route('landing.detail', $item->id) }}">{{ $item->name }}</a>
                                <br><b style="color:brown">{{ format_rupiah($item->price) }}</b>
                                @if ($item->stock < 1)
                                    <br><b style="color:red">(Habis)</b>
                                @else
                                    <br><b style="color:black">Stock {{ $item->stock }}</b>
                                @endif
                            </h3>

                        </div>
                    @endforeach
                </div>
                <center>
                    {{ $property->links() }}
                </center>
            </div>
        </div>
    </div>
    {{-- <!-- end project section -->
    <!-- three_box section -->
    <div class="three_box">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="gift_box">
                        <i><img src="images/icon_mony.png"></i>
                        <span>Money Back</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gift_box">
                        <i><img src="images/icon_gift.png"></i>
                        <span>Special Gift</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gift_box">
                        <i><img src="images/icon_car.png"></i>
                        <span>Free Shipping</span>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
