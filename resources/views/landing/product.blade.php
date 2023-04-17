@extends('landing.landing')

@section('content')
    <div class="blue_bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>{{ $thisCategory->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="project" class="project">
        <div class="container">
            <div class="row">
                <form action="{{ route('landing.product', $thisCategory->id) }}" method="GET">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="sub_category" class="form-label">Select Sub Category To Filter</label>
                            <select id="sub_category" name="sub_category" class="form-select">
                                <option value="">---SELECT---</option>
                                @foreach ($subCategory as $sc)
                                <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4">
                    <div class="search">
                        {{-- <form action="{{ route('landing.product') }}">
                            <div class="d-flex">
                                <select name="category" class="form-select" aria-label="Default select example">
                                    <option value="{{ null }}">Filter Category</option>
                                    @foreach ($category as $item)
                                        <option value={{ $item->id }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                            </div>

                        </form> --}}
                    </div>
                </div>
            </div>
            <div class="product_main">
                @foreach ($property as $item)
                    <div class="project_box">
                        <div class="dark_white_bg">
                            <img src="{{ asset('upload/admin/property/' . $item->img) }}" alt="{{ asset($item->name) }}" />
                        </div>
                        <h3>
                            <a href="{{ route('landing.detail', $item->id) }}" @if($item->stock < 1) style="pointer-events: none; cursor: default;" @endif>{{ $item->name }}</a>
                            <br><b style="color:brown">{{ format_rupiah($item->price) }}</b>
                            @if ($item->stock < 1)
                            <br><b style="color:red">(Habis)</b>
                            @else
                            <br><b style="color:black">Stock {{ $item->stock }}</b>
                            @endif
                        </h3>
                    </div>
                @endforeach
                @if ($count == 0)
                    <div class="col-md-12">

                        <h2 class="center"><b>Barang Tidak Ditemukan</b></h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection
