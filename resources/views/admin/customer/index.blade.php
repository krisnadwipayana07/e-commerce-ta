@extends('layouts.admin')

@section('page-title')
Halaman Pelanggan
@endsection

@section('page-parent-route')
Pelanggan
@endsection

@section('page-active-route')
Index
@endsection

@section('page-action-button')
<a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-success"><i class="ri-add-circle-line"></i> Add Data</a>
@endsection

@section('page-content-title')
Pelanggan Data
@endsection

@section('page-content-desc')
Halaman untuk mengatur Data Pelanggan
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
                                Tambah Pelanggan
                            </h6>
                        </div>
                        <div class="col-md text-end my-auto">
                            <a href="javascript:;" onclick="show_hide_insert()" class="btn btn-sm btn-danger"><i class="ri-close-fill"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customer.store') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        {{-- <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">-pilih-</option>
                                @foreach (returnStatus() as $key => $value)
                                    <option value="{{ $key }}" {{ (old('status') == $key) ? "selected" : "" }}>{{ $value }}</option>
                        @endforeach
                        </select> --}}
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                    @error('name')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="address" id="" cols="30" rows="10" class="form-control  @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                    @error('address')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>No Telepon</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}">
                                    @error('phone_number')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                    @error('email')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}">
                                    @error('username')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}">
                                    @error('password')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    @error('password_confirmation')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <input type="file" class="form-control @error('myimg') is-invalid @enderror" name="myimg" value="{{ old('myimg') }}" id="myimg">
                                    @error('myimg')<small class="invalid-feedback">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-12 my-2">
                                <img id="preview-myimg" src="https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png" style="height: 300px;">
                            </div>
                        </div>
                        <div class="text-end my-3">
                            <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
                            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Simpan</button>
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
                    <th>Nama</th>
                    <th>No Telp</th>
                    <th>Aksi</th>
                </thead>
            </table>
        </div>
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

    // @if(!$errors-> any())
    // $('#insert-box').hide();
    // @endif

    function show_hide_insert() {
        $('#insert-box').toggle('fast', 'swing');
    }
    $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.customer.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'phone_number',
                name: 'phone_number'
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