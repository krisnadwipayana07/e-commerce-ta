@extends('landing.landing')

@section('sidebar')
@include('landing.sidebar')
@endsection

@section('content')
<div class="container">
    @foreach ($notifications as $notif)
    <div class="row mt-3">
        <div class="card @if ($notif->is_read) text-dark bg-light @else text-white bg-primary @endif mb-3">
            <div class="card-header">{{ $notif->type }}</div>
            <div class="card-body">
                <p class="card-text">{{ $notif->message }}</p>
                <a href="javascript:;" onclick="penuliskode_modal('Read Notification', '{{ route("customer.notification.show", $notif->id) }}')" class="btn @if (!$notif->is_read) btn-light @else btn-primary @endif">Read</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection