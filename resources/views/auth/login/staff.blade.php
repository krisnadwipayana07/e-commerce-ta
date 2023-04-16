@extends('layouts.auth')
@section('page-content-title')
    Login to Staff Account
@endsection
@section('page-content-body')
    <form method="POST" action="{{ route('auth.login.login_staff') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
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
                <button type="submit" class="btn w-100 btn-primary">Submit</button>
            </div>
        </div>
    </form>
    {{-- <div class="text-center my-3">
        <a class="text-muted"href="{{ route('auth.register.form_staff') }}">Register</a>
    </div> --}}
@endsection