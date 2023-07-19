<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Kode</h6>{{ $data->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nama Penerima</h6>{{ $data->recipient_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Dikirim Ke</h6>{{ $data->deliver_to }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Transaksi Detail</h6>
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
    <h6 class="font-weight-bold">Total Pembayaran</h6>{{ format_rupiah($data->total_payment) }}
</div>
@if($data->category_payment->name == "Kredit" || $data->category_payment->name == "Credit")
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Kredit Periode</h6>{{ format_rupiah($data->total_payment / $data->credit_period) }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Bayaran Perbulan</h6>{{ format_rupiah($data->payment_credit) }}
</div>
@endif
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Metode Pembayaran</h6>{{ $data->category_payment->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Tanggal Transaksi</h6>{{ $data->created_at }}
</div>
{{--
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Gambar</h6>
    <img id="preview-myimg" src="{{ ($data->img) ? url('/upload/admin/transaction/', $data->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
--}}
<form method="POST" action="{{ route('admin.transaction.update', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Status</h6>
        <input type="text" class="form-control" value="{{ $data->status }}" disabled>
        {{--
        <select name="status" class="form-control @error('status') is-invalid @enderror">
            <option value="">-Choose-</option>
            @foreach (returnStatusOrder() as $key => $value)
                <option value="{{ $key }}" {{ (old('status', $data->status) == $key) ? "selected" : "" }}>{{ $value }}</option>
        @endforeach
        </select>
        @error('status')<small class="invalid-feedback">{{ $message }}</small>@enderror
        --}}
    </div>
    {{--
    <div class="border-bottom py-3">
        <button type="reset" class="btn btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Ubah</button>
    </div>
    --}}
</form>
<script>
    $(document).ready(function() {
        // for display myimg
        $('#myimg').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-myimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
{{-- <div class="border-bottom py-3">
    <h6 class="font-weight-bold">Created</h6>{{ format_datetime($data->created_at) }}
</div>
<div class="pt-3">
    <h6 class="font-weight-bold">Updated</h6>{{ format_datetime($data->updated_at) }}
</div> --}}