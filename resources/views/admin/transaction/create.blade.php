@extends('layouts.admin')

@section('page-title')
    Transaction Page
@endsection

@section('page-parent-route')
    Transaction 
@endsection

@section('page-active-route')
    Add
@endsection

@section('page-back-button')
    <a href="{{ route('admin.transaction.index') }}" class="my-2 btn btn-secondary btn-sm"><i class="ri-arrow-left-circle-line"></i> Back</a>
@endsection

@section('page-content-title')
    Transaction Data
@endsection

@section('page-content-desc')
    Page to manage add transaction data
@endsection

@section('page-content-body')

    <div class="row">
        <div class="col-12">
            <div>
                <div class="card mb-4 shadow-none">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.transaction.store') }}" enctype="multipart/form-data" id="form">
                            @csrf
                            @method('POST')
                            <div class="row gx-5">
                                <div class="col-md-8">
                                    <div class="row">
                                        @foreach ($category_property as $main_category)
                                            @if (count($main_category->sub_category_property))
                                                <h3>{{ $main_category->name }}</h3>
                                                @foreach ($main_category->sub_category_property as $sub_category)
                                                    @if (count($sub_category->property))
                                                        <h4>{{ $sub_category->name }}</h4>
                                                        @foreach ($sub_category->property as $property)
                                                            <div class="mb-5 col-md-6">
                                                                <div class="row g-0">
                                                                    <div class="col-md-7">
                                                                        <img src="{{ url('/upload/admin/property', $property->img) }}" class="img-fluid rounded-start" alt="...">
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <div class="card-body">
                                                                        <h5 class="card-title">{{ $property->name }}</h5>
                                                                        <p class="card-text">{{ $property->description }}</p>
                                                                        <p class="card-text"><small class="text-muted">{{ format_rupiah($property->price) }}</small></p>
                                                                        <input type="hidden" name="choose_property_id[]" value="{{ $property->id }}">
                                                                        <input type="hidden" class="input-property-price" name="choose_property_price[]" value="{{ $property->price }}">
                                                                        <input type="number" class="input-property-qty" name="choose_property_qty[]" min="0" value="0">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <div class="form-group">
                                            <label>Customer</label>
                                                <select name="customer_id" class="select-picker form-control @error('customer_id') is-invalid @enderror" style="width:100%;">
                                                    <option value="">-Choose-</option>
                                                    @foreach ($data_customer as $customer)
                                                        <option value="{{ $customer->id }}" {{ (old('customer_id') == $customer->id) ? "selected" : "" }}>{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('customer_id')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <div class="form-group">
                                            <label>Recipient</label>
                                                <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" name="recipient_name" value="{{ old('recipient_name') }}">
                                                @error('recipient_name')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <div class="form-group">
                                            <label>Deliver To</label>
                                                <textarea type="text" class="form-control @error('deliver_to') is-invalid @enderror" name="deliver_to">{{ old('deliver_to') }}</textarea>
                                                @error('deliver_to')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <div class="form-group">
                                            <label>Payment Method</label>
                                                <select name="category_payment_id" class="form-control @error('category_payment_id') is-invalid @enderror">
                                                    <option value="">-Choose-</option>
                                                    @foreach ($category_payment as $item)
                                                        <option value="{{ $item->id }}" {{ (old('category_payment_id') == $item->id) ? "selected" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_payment_id')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <div class="form-group">
                                            <label></label>
                                                <input type="hidden" name="total_payment" value="0" class="total-price form-control">
                                                <h3 class="mt-3">
                                                    <span class="">Total Price</span>
                                                    <span class="float-end total-price-text">Rp 0</span>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end my-3">
                                        <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            // initiate input spinner
            $(".input-property-qty").inputSpinner({
                buttonsOnly: true,
            });
        });
        $(".input-property-qty").on("change", function() {
            var data = [],
            propertyPrice = document.querySelectorAll('input[type="hidden"].input-property-price');
            propertyQty = document.querySelectorAll('input[type="number"].input-property-qty');
            for (var i = 0; i < propertyQty.length; i++) {
                data.push(
                    {
                        'price': parseInt(propertyPrice[i].value),
                        'qty': parseInt(propertyQty[i].value),
                        'totalPrice': parseInt(propertyPrice[i].value) * parseInt(propertyQty[i].value)
                    }
                );
            }
            var totalPrice = data.reduce(function(a, b){ return parseInt(a) + parseInt(b.totalPrice)}, 0);
            $(".total-price").val(totalPrice);
            var formatRupiah = (money) => {
                return new Intl.NumberFormat('id-ID',
                    { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }
                ).format(money);
            };
            $(".total-price-text").text(formatRupiah(totalPrice));
            // console.log(data);
            // console.log(totalPrice);
        })
    </script>
@endsection