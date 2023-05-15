@extends('layouts.auth')
@section('page-content-title')
    <b>Halaman Log in</b>
@endsection
@section('page-content-body')
    <form method="POST" action="{{ route('auth.login.login_customer') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-group">
                <label><h6><b>Username</h6></b></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"  placeholder="Masukan Username"
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
                    <label><h6><b>Password</h6></b></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"  placeholder="Masukan Password"
                        value="{{ old('password') }}">
                    @error('password')
                        <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md">
                <button type="submit" class="btn w-100 btn-sm btn btn-outline-success">Log in</button> 
            </div>
        </div>
    </form>
@endsection
