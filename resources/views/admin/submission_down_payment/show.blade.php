<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transaction Code</h6>{{ $data->transaction->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Customer Name</h6>{{ $data->customer->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Down Payment</h6>{{ format_rupiah($data->transaction->down_payment) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Phone Number</h6>{{ $data->transaction->account_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Tanggal Pengiriman</h6>{{ $data->updated_at }}
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
{{-- <div class="border-bottom py-3">
    <h6 class="font-weight-bold">Credit Periode</h6>{{ $data->transaction->credit_period }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Bayaran Perbulan</h6>{{ format_rupiah($data->transaction->payment_credit) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Remaining Instalment</h6>{{ ($data->transaction->credit_period - $data->transaction->total_phase) }}x
</div>
--}}
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Evidence Payment</h6>
    <img id="preview-ktp"
        src="{{ $data->evidence_payment ? url('/upload/customer/submission_dp_payment/', $data->evidence_payment) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
        alt="preview image" style="height: 300px;">
</div>
@if ($data->status != 'accept')
    <div class="row">
        <div class="col-md-2">
            <form method="POST" action="{{ route('admin.submission.dp.payment.approve', $data->id) }}">
                @csrf
                @method('PUT')
                <div class="border-bottom py-3">
                    <button type="submit" class="btn btn-success"><i
                            class="fa fa-fw fa-paper-plane me-1"></i>Approve</button>
                </div>
            </form>
        </div>
        <div class="col-md-2">
            <form method="POST" action="{{ route('admin.submission.dp.payment.reject', $data->id) }}">
                @csrf
                @method('PUT')
                <div class="border-bottom py-3">
                    <button type="submit" class="btn btn-danger"><i
                            class="fa fa-fw fa-paper-plane me-1"></i>Reject</button>
                </div>
            </form>
        </div>
    </div>
@endif
