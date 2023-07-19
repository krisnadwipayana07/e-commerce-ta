@extends('layouts.admin')

@section('page-title')
Halaman Barang
@endsection

@section('page-parent-route')
Barang
@endsection

@section('page-active-route')
Edit
@endsection

@section('page-back-button')
<a href="{{ route('admin.property.index') }}" class="my-2 btn btn-secondary btn-sm"><i class="ri-arrow-left-circle-line"></i> Kembali</a>
@endsection

@section('page-content-title')
Ubah Barang Data
@endsection

@section('page-desc')
Halaman untuk mengubah Barang Data
@endsection

@section('page-content-body')
<div class="row">
    <div class="col-md">
        <form method="POST" action="{{ route('admin.property.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="sub_category_property_id" class="select-picker form-control @error('sub_category_property_id') is-invalid @enderror" style="width:100%;">
                            <option value="">-pilih-</option>
                            @foreach ($category_property as $category)
                            <optgroup label="{{ $category->name }}">
                                {{-- <option disabled>{{ $category->name }}</option> --}}
                                @foreach ($category->sub_category_property as $sub_category)
                                <option value="{{ $sub_category->id }}" {{ old('sub_category_property_id', $data->sub_category_property_id) == $sub_category->id ? 'selected' : '' }}>
                                    {{ $sub_category->name }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        @error('sub_category_property_id')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $data->name) }}">
                        @error('name')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control  @error('description') is-invalid @enderror">{{ old('description', $data->description) }}</textarea>
                        @error('description')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $data->price) }}">
                        @error('price')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ old('stock', $data->stock) }}">
                        @error('stock')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">-Choose-</option>
                            @foreach (returnStatus() as $key => $value)
                            <option value="{{ $key }}" {{ old('status', $data->status) == $key ? 'selected' : '' }}>{{ $value }}
                            </option>
                            @endforeach
                        </select>
                        @error('status')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md">
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" class="form-control @error('myimg') is-invalid @enderror" name="myimg" value="{{ old('myimg') }}" id="myimg">
                        @error('myimg')
                        <small class="invalid-feedback">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 my-2">
                    <img id="preview-myimg" src="{{ $data->img ? url('/upload/admin/property/', $data->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
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


@section('page-js')
<script>
    $(document).ready(function() {
        // for display myimg
        $('#myimg').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-myimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
@endsection