@extends('layouts.admin')

@section('page-title')
    Property Page
@endsection

@section('page-parent-route')
    Property
@endsection

@section('page-active-route')
    Tambah Stock
@endsection

@section('page-back-button')
    <a href="{{ route('admin.property.index') }}" class="my-2 btn btn-secondary btn-sm"><i class="ri-arrow-left-circle-line"></i> Back</a>
@endsection

@section('page-content-title')
    Tambah Stock Property
@endsection

@section('page-desc')
    Page to edit property data
@endsection

@section('page-content-body')
<div class="row">
    <div class="col-md">
        <form method="POST" action="{{ route('admin.property.store_stock', $data->id) }}">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="0" min="0">
                        @error('stock')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="text-end my-3">
                <button type="reset" class="btn btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

