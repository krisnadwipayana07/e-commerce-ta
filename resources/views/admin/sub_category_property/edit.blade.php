@extends('layouts.admin')

@section('page-title')
Halaman Sub Kategori Barang
@endsection

@section('page-parent-route')
Sub Kategori Barang
@endsection

@section('page-active-route')
Edit
@endsection

@section('page-back-button')
<a href="{{ route('admin.sub_category_property.index') }}" class="my-2 btn btn-secondary btn-sm"><i class="ri-arrow-left-circle-line"></i> Back</a>
@endsection

@section('page-content-title')
Edit Sub Kategori Barang Data
@endsection

@section('page-desc')
Halaman untuk mengubah sub kategori Barang data
@endsection

@section('page-content-body')
<div class="row">
    <div class="col-md">
        <form method="POST" action="{{ route('admin.sub_category_property.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_property_id" class="form-control @error('category_property_id') is-invalid @enderror">
                            <option value="">-pilih-</option>
                            @foreach ($category_property as $item)
                            <option value="{{ $item->id }}" {{ (old('category_property_id', $data->category_property_id) == $item->id) ? "selected" : "" }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('category_property_id')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $data->name) }}">
                        @error('name')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">-pilih-</option>
                            @foreach (returnStatus() as $key => $value)
                            <option value="{{ $key }}" {{ (old('status', $data->status) == $key) ? "selected" : "" }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('status')<small class="invalid-feedback">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
            <div class="text-end my-3">
                <button type="reset" class="btn btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection