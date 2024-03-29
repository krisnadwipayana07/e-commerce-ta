<div class="border-bottom pb-3">
    <h6 class="font-weight-bold">Gambar</h6>
    <div class="row">
        <div class="col-12 text-center">
            <img src="{{ url('/upload/admin/property', $data->img) }}" alt="" class="rounded-circle" style="height:150px;width:150px;">
        </div>
    </div>
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Kategori</h6>{{ $data->sub_category_property->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nama</h6>{{ $data->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Deskripsi</h6>{{ $data->description }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Harga</h6>{{ format_rupiah($data->price) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Stok</h6>{{ $data->stock }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Status</h6>{{ returnStatus($data->status) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Dibuat</h6>{{ format_datetime($data->created_at) }}
</div>
<div class="pt-3">
    <h6 class="font-weight-bold">Diubah</h6>{{ format_datetime($data->updated_at) }}
</div>