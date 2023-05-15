@extends('layouts.auth')
@section('page-content-title')
    <b>Daftar Akun</b>
@endsection
@section('page-content-body')
    <form method="POST" action="{{ route('auth.register.register_customer') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="address" id="" cols="30" rows="10"
                        class="form-control  @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                        name="phone_number" value="{{ old('phone_number') }}">
                    @error('phone_number')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}">
                    @error('email')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Jenis Kelamin</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="maleRadio" value="Male">
                        <label class="form-check-label" for="maleRadio">Laki-Laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="femaleRadio" value="Female">
                        <label class="form-check-label" for="femaleRadio">Perempuan</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                        value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                        value="{{ old('username') }}">
                    @error('username')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        value="{{ old('password') }}">
                    @error('password')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" value="{{ old('password_confirmation') }}">
                    @error('password_confirmation')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                    <label>Foto Profil</label>
                    <input type="file" class="form-control @error('myimg') is-invalid @enderror" name="myimg"
                        value="{{ old('myimg') }}" id="myimg">
                    @error('myimg')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 my-2">
                <img id="preview-myimg" src="https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png"
                    style="height: 300px;">
            </div>
        </div>
        <div class="text-end my-3">
            <!-- <button type="reset" class="btn w-40 btn-sm btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Atur
                Ulang</button> -->
            <button type="submit" class="btn w-100 btn-sm btn-success"><i
                    class="fa fa-fw fa-paper-plane me-1"></i>Daftar</button>
        </div>
    </form>
@endsection
