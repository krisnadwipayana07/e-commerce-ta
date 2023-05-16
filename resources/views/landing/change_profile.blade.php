@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Profile Saya</h2>
                    </div>
                </div>
            </div>
            <form action="{{ route('customer.profile.update', ['customer' => $customer->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-9">
                        <div class="row col-12">
                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">
                                    Username
                                </div>
                                <div class="col-9">
                                    {{-- <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        name="username" value="{{ old('username', $customer->username) }}">
                                    @error('username')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror --}}
                                    {{ $customer->username }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">
                                    Nama
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $customer->name) }}">
                                    @error('name')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">Email</div>
                                <div class="col-9">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $customer->email) }}">
                                    @error('email')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">
                                    Nomor Telepon
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}">
                                    @error('phone_number')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end">
                                    <label>Alamat</label>
                                </div>
                                <div class="col-9">
                                    <textarea name="address" id="" rows="3" class="form-control  @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>
                                    @error('address')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end">
                                    <label>Jenis Kelamin</label><br>
                                </div>
                                <div class="col-9 d-flex align-items-center">
                                    <input class="mr-1" type="radio" name="gender" id="maleRadio" value="Male"
                                        @if ($customer->gender == 'Male') checked @endif>
                                    <label class="form-check-label" for="maleRadio">Laki-Laki</label>

                                    <input class="ml-3 mr-1" type="radio" name="gender" id="femaleRadio" value="Female"
                                        @if ($customer->gender == 'Female') checked @endif>
                                    <label class="form-check-label" for="femaleRadio">Perempuan</label>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">
                                    Tanggal Lahir
                                </div>
                                <div class="col-9">
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                        name="birth_date" value="{{ old('birth_date', $customer->birth_date) }}">
                                    @error('birth_date')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">
                                    Kata Sandi
                                </div>
                                <div class="col-9">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" value="{{ old('password') }}">
                                    @error('password')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3 d-flex justify-content-end align-items-center">
                                    Kata Sandi Baru
                                </div>
                                <div class="col-9">
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    @error('password_confirmation')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3"></div>
                                <div class="col-9 my-3">
                                    {{-- <button type="reset" class="btn btn-danger mr-2">
                                        <i class="fa fa-fw fa-undo me-1"></i>Reset</button> --}}
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class='mt-4 d-flex flex-column justify-content-center align-items-center'>
                            <img id="preview-myimg" class="rounded-circle"
                                src="{{ $customer->img ? url('/upload/admin/customer/', $customer->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                                alt="preview image" style="height: 200px; width: 200px; object-fit:cover">

                            <div class="mt-4">
                                <input type="file" class="form-control @error('myimg') is-invalid @enderror"
                                    name="myimg" value="{{ old('myimg') }}" id="myimg">
                                @error('myimg')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <p class="mt-2">Ukuran file gambar maksimal 1 MB</p>


                            @if (auth()->guard('customer')->user()->allow_credit)
                                <br><button type="button" class="btn btn-success px-5">Akun Premium</button>
                            @else
                                @if ($isPremium)
                                    <br><button type="button" class="btn btn-warning">Sedang Ditinjau</button>
                                @else
                                    <br><a href="{{ route('customer.profile.submission_premium.index') }}"
                                        class="btn btn-primary ">Upgrade Akun</a>
                                @endif
                            @endif
                            {{-- <div class="form-group">
                                <input type="image" class="form-control @error('myimg') is-invalid @enderror"
                                    name="myimg" value="{{ old('myimg') }}" id="myimg" style="display: none;">
                                @error('myimg')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                                <input type="button" class="btn btn-light mt-2" value="Pilih Gambar"
                                    onclick="document.getElementById('myimg').click();" />
                            </div> --}}
                        </div>
                    </div>
                </div>
            </form>
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
    </script>
@endsection
