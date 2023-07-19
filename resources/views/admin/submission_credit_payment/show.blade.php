<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Kode Transaksi</h6>{{ $data->transaction->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nama Pelanggan</h6>{{ $data->customer->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Periode Kredit</h6>{{ $data->transaction->credit_period }} Bulan
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Bayaran Perbulan</h6>{{ format_rupiah($data->transaction->payment_credit) }}
</div>
@if ($data->status != "accept")
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Tenggat Bulan Ini</h6>{{ $data->transaction->due_date }}
</div>
@if ($overPrice)
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Denda</h6>{{ format_rupiah($overPrice) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Total dengan Denda</h6>{{ format_rupiah($overPrice + $data->transaction->payment_credit) }}
</div>
@endif
@endif
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">No Telepon</h6>{{ $data->transaction->account_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Sisa Pembayaran</h6>{{ ($data->transaction->credit_period - $data->transaction->total_phase) }}x
</div>
<div>
    <h5 class="text-center mt-3">Detail Transaksi</h5>
    <table class="table table-borderless">
        <tbody>
            @foreach ($data->transaction->transaction_detail as $item)
            <tr>
                <td>
                    {{ $item->property->name }}
                </td>
                <td>
                    {{ format_rupiah($item->price) }}
                </td>
                <td>
                    x {{ $item->qty }}
                </td>
                <td>
                    = {{ format_rupiah($item->total_price) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Bukti Pembayaran</h6>
    <img id="preview-ktp" src="{{ ($data->evidence_payment) ? url('/upload/customer/submission_credit_payment/', $data->evidence_payment) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Alamat</h6>
    <div id="map" class="my-3" style="height: 280px;"></div>
    <input type="hidden" id="latitude" name="lat" value="{{ $data->transaction->latitude ?: '' }}">
    <input type="hidden" id="longitude" name="lng" value="{{ $data->transaction->longitude ?: '' }}">
</div>
<div class="row">
    <div class="col-md-2">
        <form method="POST" action="{{ route('admin.submission.credit.payment.approve', $data->id) }}">
            @csrf
            @method('PUT')
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Setuju</button>
            </div>
        </form>
    </div>
    <div class="col-md-2">
        <form method="POST" action="{{ route('admin.submission.credit.payment.reject', $data->id) }}">
            @csrf
            @method('PUT')
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-danger"><i class="fa fa-fw fa-paper-plane me-1"></i>Tolak</button>
            </div>
        </form>
    </div>
</div>