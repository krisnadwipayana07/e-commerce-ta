<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transaction Code</h6>{{ $data->transaction->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Customer Name</h6>{{ $data->customer->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Credit Periode</h6>{{ $data->transaction->credit_period }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Payment Per Month</h6>{{ format_rupiah($data->transaction->payment_credit) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Phone Number</h6>{{ $data->transaction->account_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Remaining Instalment</h6>{{ ($data->transaction->credit_period - $data->transaction->total_phase) }}x
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Evidence Payment</h6>
    <img id="preview-ktp" src="{{ ($data->evidence_payment) ? url('/upload/customer/submission_credit_payment/', $data->evidence_payment) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Address</h6>
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
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Approve</button>
            </div>
        </form>
    </div>
    <div class="col-md-2">
        <form method="POST" action="{{ route('admin.submission.credit.payment.reject', $data->id) }}">
            @csrf
            @method('PUT')
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-danger"><i class="fa fa-fw fa-paper-plane me-1"></i>Reject</button>
            </div>
        </form>
    </div>
</div>