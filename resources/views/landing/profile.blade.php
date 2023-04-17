@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Profile</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <img id="preview-myimg" src="{{ ($user->img) ? url('/upload/admin/customer/', $user->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
                </div>
                <div class="col-md-8">
                    <label>Name:</label>
                    <b>{{ $user->name }}</b>
                    <br><label>Address:</label>
                    <b>{{ $user->address }}</b>
                    <br><label>Phone Number:</label>
                    <b>{{ $user->phone_number }}</b>
                    <br><label>Email:</label>
                    <b>{{ $user->email }}</b>
                    <br><label>Advance</label>
                    <br><a href="{{ route('customer.profile.edit', ['customer' => $user->id]) }}" class="btn btn-secondary mb-1">Change Profile</a>
                    {{--
                        <br><a href="{{ route('customer.profile.change.password.index', ['customer' => $user->id]) }}" class="btn btn-secondary">Change Password</a>
                    --}}
                    <br>
                    @if (auth()->guard('customer')->user()->allow_credit)
                    <br><button type="button" class="btn btn-success">This account now premium</button>
                    @else
                    @if ($isPremium)
                    <br><button type="button" class="btn btn-warning">Premium account in process</button>
                    @else
                    <br><a href="{{ route('customer.profile.submission_premium.index') }}" class="btn btn-primary">Go To Premium</a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection