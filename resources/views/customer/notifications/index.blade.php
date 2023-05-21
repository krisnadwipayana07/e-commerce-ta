@extends('landing.landing')

@section('sidebar')
    @include('landing.sidebar')
@endsection

@section('content')
    <div class="container">
        @foreach ($notifications as $notif)
            <div class="row mt-3">
                <div
                    class="card @if ($notif->is_read) text-dark bg-light @else text-white bg-primary @endif mb-3">
                    <div class="card-header">{{ $notif->type }}</div>
                    <div class="card-body">
                        <p class="card-text">{{ $notif->message }}</p>
                        {{-- <a href="javascript:;" onclick="penuliskode_modal('Read Notification', '{{ route("customer.notification.show", $notif->id) }}')" class="btn @if (!$notif->is_read) btn-light @else btn-primary @endif">Read</a> --}}
                        <div class="d-flex gap-2">
                            @if ($notif->transaction_id != null)
                                @if ($notif->type === 'Transaction - Warning' && $notif->status == 'pending')
                                    <a class="btn btn-light btn-primary"
                                        href="{{ route('customer.notification.transaction.edit', $notif->transaction_id) }}">Ubah
                                        Data</a>
                                @elseif ($notif->type === 'Delivery - Notification' && !$notif->reply)
                                    <form action="{{ route('customer.notification.transaction.reply') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $notif->id }}">
                                        <input type="hidden" name="reply" value="Iya">
                                        <button type="submit" class="btn btn-success">Iya</button>
                                    </form>
                                    <form action="{{ route('customer.notification.transaction.reply') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $notif->id }}">
                                        <div class="d-flex gap-2">
                                            <input name="reply" placeholder="sarankan hari lain" class="form-control">
                                            <button type="submit" class="btn btn-warning">Sarankan</button>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
