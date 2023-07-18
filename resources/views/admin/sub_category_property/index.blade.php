@extends('layouts.admin')

@section('page-title')
Halaman Sub Kategori Barang
@endsection

@section('page-parent-route')
Sub Kategori Barang
@endsection

@section('page-active-route')
Index
@endsection

@section('page-action-button')
<a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-success"><i class="ri-add-circle-line"></i> Tambah Data</a>
@endsection

@section('page-content-title')
Sub Kategori Barang Data
@endsection

@section('page-content-desc')
Halaman to mengatur sub kategori Barang data
@endsection

@section('page-content-body')

<div class="row">
    <div class="col-12">
        <div id="insert-box">
            <div class="card mb-4 shadow-none">
                <div class="card-header py-2 mb-3">
                    <div class="row">
                        <div class="col-md">
                            <h6 class="my-2 font-weight-bold text-dark">
                                {{-- <i class="ri-add-box-line btn btn-sm"></i> --}}
                                Tambah Sub Kategori Barang Data
                            </h6>
                        </div>
                        <div class="col-md text-end my-auto">
                            <a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-danger"><i class="ri-close-fill"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.sub_category_property.store') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        {{-- <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">-Pilih-</option>
                                @foreach (returnStatus() as $key => $value)
                                    <option value="{{ $key }}" {{ (old('status') == $key) ? "selected" : "" }}>{{ $value }}</option>
                        @endforeach
                        </select> --}}
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <select name="category_property_id" class="form-control @error('category_property_id') is-invalid @enderror">
                                        <option value="">-pilih-</option>
                                        @foreach ($category_property as $item)
                                        <option value="{{ $item->id }}" {{ (old('category_property_id') == $item->id) ? "selected" : "" }}>{{ $item->name }}</option>
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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                    @error('name')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-end my-3">
                            <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
    function show_hide_insert() {
        $('#insert-box').toggle('fast', 'swing');
    }
    $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.sub_category_property.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'category_property_id',
                name: 'category_property_id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        order: [
            [0, "name"]
        ]
    });
</script>
@endsection