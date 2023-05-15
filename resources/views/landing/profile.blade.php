@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Profil Saya</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="row my-4">
                        <div class="col-3 d-flex justify-content-end align-items-center">Nama Pengguna</div>
                        <div class="col"style="font-weight: 600">
                            {{ $user->username }}
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-3 d-flex justify-content-end align-items-center">Nama</div>
                        <div class="col"style="font-weight: 600">{{ $user->name }}</div>
                    </div>
                    <div class="row my-4">
                        <div class="col-3 d-flex justify-content-end align-items-center">Email</div>
                        <div class="col"style="font-weight: 600">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-3 d-flex justify-content-end align-items-center">Nomor Telepon</div>
                        <div class="col"style="font-weight: 600">
                            {{ $user->phone_number }}
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-3 d-flex justify-content-end align-items-center">Alamat</div>
                        <div class="col"style="font-weight: 600">
                            {{ $user->address }}
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-3"></div>
                        <div class="col">
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
                        </div>
                    </div>

                </div>
                <div class="col-md-3 d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <img id="preview-myimg" class="rounded-circle"
                            src="{{ $user->img ? url('/upload/admin/customer/', $user->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                            alt="preview image" style="height: 200px; width: 200px; object-fit:cover">
                    </div>
                    <div>
                        <br><a href="{{ route('customer.profile.edit', ['customer' => $user->id]) }}"
                            class="btn btn-secondary mb-1">Edit Profil</a>
                        {{--
                                <br><a href="{{ route('customer.profile.change.password.index', ['customer' => $user->id]) }}" class="btn btn-secondary">Change Password</a>
                                --}}
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
