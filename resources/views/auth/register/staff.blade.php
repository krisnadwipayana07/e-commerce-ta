@extends('layouts.auth')
@section('page-content-title')
    Register as Staff
@endsection
@section('page-content-body')
<form method="POST" action="{{ route('auth.register.register_staff') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    {{-- <select name="status" class="form-control @error('status') is-invalid @enderror">
        <option value="">-Choose-</option>
        @foreach (returnStatus() as $key => $value)
            <option value="{{ $key }}" {{ (old('status') == $key) ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
    </select> --}}
    <div class="row mb-3">
        <div class="col-md">
            <div class="form-group">
            <label>ID Number</label>
                <input type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}">
                @error('id_number')<small class="invalid-feedback">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md">
            <div class="form-group">
            <label>Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                @error('name')<small class="invalid-feedback">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md">
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" id="" cols="30" rows="10" class="form-control  @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                @error('address')<small class="invalid-feedback">{{ $message }}</small>@enderror

            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md">
            <div class="form-group">
            <label>Phone Number</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}">
                @error('phone_number')<small class="invalid-feedback">{{ $message }}</small>@enderror
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
            <label>Password Confirmation</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}">
                @error('password_confirmation')<small class="invalid-feedback">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md">
            <div class="form-group">
            <label>Image</label>
                <input type="file" class="form-control @error('myimg') is-invalid @enderror" name="myimg" value="{{ old('myimg') }}" id="myimg">
                @error('myimg')<small class="invalid-feedback">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="col-md-12 my-2">
            <img id="preview-myimg" src="https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png" style="height: 300px;">
        </div>
    </div>
    <div class="text-center my-3">
        <button type="submit" class="w-100 btn btn-primary text-white fw-bold "><i class="fa fa-fw fa-paper-plane me-1"></i>Submit</button>
    </div>
</form>
<div class="text-center my-3">
    <a class="text-muted"href="{{ route('auth.login.form_staff') }}">Login</a>
</div>
@endsection