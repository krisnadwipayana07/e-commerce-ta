<div class="border-bottom pb-3">
    <h6 class="font-weight-bold">Gambar</h6>
    <div class="row">
        <div class="col-12 text-center">
            <img src="{{ url('/upload/admin/admin', $data->img) }}" alt="" class="rounded-circle" style="height:150px;width:150px;">
        </div>
    </div>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nama</h6>{{ $data->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Alamat</h6>{{ $data->address }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">No Telp</h6>{{ $data->phone_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Email</h6>{{ $data->email }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Username</h6>{{ $data->username }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Dibuat</h6>{{ format_datetime($data->created_at) }}
</div>
<div class="pt-3">
    <h6 class="font-weight-bold">Diedit</h6>{{ format_datetime($data->updated_at) }}
</div>