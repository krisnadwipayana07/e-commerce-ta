<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Code</h6>{{ $data->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Recipient Name</h6>{{ $data->recipient_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Deliver To</h6>{{ $data->deliver_to }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Detail Transaction</h6>
    <table class="table table-borderless">
        <tbody>
            @foreach ($data->transaction_detail as $item)
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
    <h6 class="font-weight-bold">Total Payment</h6>{{ format_rupiah($data->total_payment) }}
</div>
@if($data->category_payment->name == "Kredit" || $data->category_payment->name == "Credit")
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Credit Periode</h6>{{ $data->credit_period }}x
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Payment Per Month</h6>{{ format_rupiah($data->payment_credit) }}
</div>
@endif
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Payment Method</h6>{{ $data->category_payment->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transaction Date</h6>{{ $data->created_at }}
</div>
@if($data->category_payment->name != "Cash" && $data->category_payment->name != "Cash On Delivery" && $data->category_payment->name != "Kredit" && $data->category_payment->name != "Credit")
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Image</h6>
    <img id="preview-myimg" src="{{ ($data->img) ? url('/upload/evidence_payment/', $data->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
@endif
@if ($data->category_payment->name == "Kredit" || $data->category_payment->name == "Credit")
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP Name</h6>{{ $submission->ktp_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP Number</h6>{{ $submission->ktp_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP Address</h6>{{ $submission->ktp_address }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP</h6>
    <img id="preview-ktp" src="{{ ($submission->ktp) ? url('/upload/transaction/submission_credit/', $submission->ktp) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Salary Slip</h6>
    <img id="preview-salary_slip" src="{{ ($submission->salary_slip) ? url('/upload/transaction/submission_credit/', $submission->salary_slip) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Photo Selfi</h6>
    <img id="preview-photo" src="{{ ($submission->photo) ? url('/upload/transaction/submission_credit/', $submission->photo) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
@endif
<form method="POST" action="{{ route('admin.evidence_payment.approve', $data->id) }}">
    @csrf
    @method('PUT')
    <div class="form-group py-3">
        <label>Pesan <small><em>(opsional)</em></small></label>
        <input type="text" name="message" class="form-control" placeholder="Pesan (isi jika transaksi di reject)">
    </div>
    <div class="row">
        <div class="col-md-2">
            @php
            $stt = 'paid';
            @endphp
            @if ($data->category_payment->name == 'Kredit' || $data->category_payment->name == 'Credit')
            @php
            $stt = 'in_progress';
            @endphp
            @endif
            <input type="hidden" name="status" value="{{ $stt }}">
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Approve</button>
            </div>
        </div>
        <div class="col-md-2">
            <form method="POST" action="{{ route('admin.evidence_payment.reject', $data->id) }}">
                @csrf
                @method('DELETE')
                <div class="border-bottom py-3">
                    <button type="submit" class="btn btn-danger"><i class="fa fa-fw fa-paper-plane me-1"></i>Reject</button>
                </div>
            </form>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
            // for display myimg
            $('#myimg').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => {
                $('#preview-myimg').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0   ]);
            });
        });
</script>
{{-- <div class="border-bottom py-3">
    <h6 class="font-weight-bold">Created</h6>{{ format_datetime($data->created_at) }}
</div>
<div class="pt-3">
    <h6 class="font-weight-bold">Updated</h6>{{ format_datetime($data->updated_at) }}
</div> --}}
