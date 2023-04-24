aaaa<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Code</h6>{{ $data->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Recipient Name</h6>{{ $data->recipient_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Deliver To</h6>{{ $data->deliver_to }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Phone Number</h6>{{ $data->account_number }}
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
<h2 class="pt-5 fw-bold">Detail User Kredit</h2>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Full Name</h6>{{ $submission->full_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nickname</h6>{{ $submission->nickname }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Mother Name</h6>{{ $submission->mother_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Mother Name</h6>{{ $submission->post_code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Birth Place</h6>{{ $submission->birth_place }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Birth Date</h6>{{ $submission->birth_date }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Gender</h6>{{ $submission->gender }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Home State</h6>{{ $submission->home_state }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Long Occupied</h6>{{ $submission->long_occupied }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Education</h6>{{ $submission->education }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Maritial Status</h6>{{ $submission->marital_status }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Job</h6>{{ $submission->jobs }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Company Name</h6>{{ $submission->company_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Company Address</h6>{{ $submission->company_address }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Company Phone</h6>{{ $submission->company_phone }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Length Of Work</h6>{{ $submission->length_of_work }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Income Amount</h6> @currency($submission->income_amount)
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Extra Income</h6> @currency($submission->extra_income)
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Spending</h6>@currency($submission->spending)
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Residual Income</h6>@currency($submission->residual_income)
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transportation Type</h6>{{ $submission->transportation_type }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transportation Brand</h6>{{ $submission->transportation_brand }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Year Of Purchase</h6>{{ $submission->year_of_purchase }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Police Number</h6>{{ $submission->police_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transportation Color</h6>{{ $submission->transportation_color }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">BPKB Number</h6>{{ $submission->bpkb_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Number Of Bank Account</h6>{{ $submission->rekening_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Name Of Bank Account</h6>{{ $submission->bank }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Owner Of Bank Account</h6>{{ $submission->owner_rekening }}
</div>
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
    <a target="_blank" href="{{ ($submission->ktp) ? url('/upload/transaction/submission_credit/', $submission->ktp) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
        <img id="preview-ktp" src="{{ ($submission->ktp) ? url('/upload/transaction/submission_credit/', $submission->ktp) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </a>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Salary Slip</h6>
    <a target="_blank" href="{{ ($submission->salary_slip) ? url('/upload/transaction/submission_credit/', $submission->salary_slip) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
        <img id="preview-salary_slip" src="{{ ($submission->salary_slip) ? url('/upload/transaction/submission_credit/', $submission->salary_slip) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </a>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Photo Selfi</h6>
    <a target="_blank" href="{{ ($submission->photo) ? url('/upload/transaction/submission_credit/', $submission->photo) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
        <img id="preview-photo" src="{{ ($submission->photo) ? url('/upload/transaction/submission_credit/', $submission->photo) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </a>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">House Image</h6>
    <a target="_blank" href="{{ ($submission->house_image) ? url('/upload/transaction/submission_credit/', $submission->house_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
        <img id="preview-photo" src="{{ ($submission->house_image) ? url('/upload/transaction/submission_credit/', $submission->house_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </a>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transportation Image</h6>
    <a target="_blank" href="{{ ($submission->transportation_image) ? url('/upload/transaction/submission_credit/', $submission->transportation_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
        <img id="preview-photo" src="{{ ($submission->transportation_image) ? url('/upload/transaction/submission_credit/', $submission->transportation_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </a> 
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Rekening Book Image</h6>
    <a target="_blank" href="{{ ($submission->rekening_book_image) ? url('/upload/transaction/submission_credit/', $submission->rekening_book_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
        <img id="preview-photo" src="{{ ($submission->rekening_book_image) ? url('/upload/transaction/submission_credit/', $submission->rekening_book_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </a>
</div>
@endif
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Address</h6>
    <div id="map" class="my-3" style="height: 280px;"></div>
    <input type="hidden" id="latitude" name="lat" value="{{ $data->latitude ?: '' }}">
    <input type="hidden" id="longitude" name="lng" value="{{ $data->longitude ?: '' }}">
</div>
<div class="form-group py-3">
    <label>Pesan Notifikasi ke User <small><em>(opsional)</em></small></label>
    <form class="d-flex">
        <input form="reject-form" type="text" name="message" class="form-control" placeholder="Pesan (isi jika transaksi di reject)">
        <button type="submit" class="btn btn-primary">Kirim Notifikasi</button>
    </form>
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
        <form hidden={{$data->status !== "in_progress"}} method="POST" action="{{ route('admin.evidence_payment.approve', $data->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="{{ $stt }}">
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Approve</button>
            </div>
        </form>
    </div>
    <div class="col-md-2">
        <form hidden={{$data->status !== "in_progress"}} id="reject-form" method="POST" action="{{ route('admin.evidence_payment.reject', $data->id) }}">
            @csrf
            @method('DELETE')
            <div class="border-bottom py-3">
                <button form="reject-form" type="submit" class="btn btn-danger"><i class="fa fa-fw fa-paper-plane me-1"></i>Reject</button>
            </div>
        </form>
    </div>
</div>
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
