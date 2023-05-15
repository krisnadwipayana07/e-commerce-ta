<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP Name</h6>{{ $data->ktp_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP Number</h6>{{ $data->ktp_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP Address</h6>{{ $data->ktp_address }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">KTP</h6>
    <img id="preview-ktp" src="{{ ($data->ktp) ? url('/upload/customer/submission_premium/', $data->ktp) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Salary Slip</h6>
    <img id="preview-salary_slip" src="{{ ($data->salary_slip) ? url('/upload/customer/submission_premium/', $data->salary_slip) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Photo Selfie</h6>
    <img id="preview-photo" src="{{ ($data->photo) ? url('/upload/customer/submission_premium/', $data->photo) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
<div class="row">
    <div class="col-md-2">
        <form method="POST" action="{{ route('admin.submission_premium.approve', $data->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="1">
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Approve</button>
            </div>
        </form>
    </div>
    <div class="col-md-2">
        <form method="POST" action="{{ route('admin.submission_premium.reject', $data->id) }}">
            @csrf
            @method('DELETE')
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-danger"><i class="fa fa-fw fa-paper-plane me-1"></i>Reject</button>
            </div>
        </form>
    </div>
</div>