<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Kode Transaksi</h6>{{ $transaction->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nama Penerima</h6>{{ $transaction->recipient_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Alamat Pengiriman</h6>{{ $transaction->deliver_to }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nombor Telepon</h6>{{ $transaction->account_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Total Pembayaran</h6>{{ format_rupiah($transaction->total_payment) }}
</div>
<div class="border-bottom py-3">
    <h5 class="fw-bold text-center">Detail Transaksi</h5>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Jenis Pembayaran</h6>{{ $transaction->category_payment->name }}
    </div>
    <h6 class="font-weight-bold my-3">Barang</h6>
    <table class="table table-borderless">
        <tbody>
            @foreach ($transaction->transaction_detail as $item)
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
<div class="my-5">
    <h5 class="fw-bold text-center my-2">Foto Bukti Penerimaan</h5>
    <form action="{{ route('admin.delivery.evidence.store', $transaction->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">
            <label>Foto Penerimaan Produk</label>
            <div class="col-md-12 my-2">
                <img id="preview-product_evidence" src="{{ $evidence ? url('/upload/admin/delivery/product/', $evidence->product_evidence) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
            </div>
            <div class="col-md">
                <div class="form-group">
                    <input type="file" class="form-control @error('product_evidence') is-invalid @enderror" name="product_evidence" value="{{ old('product_evidence') }}" id="product_evidence" required>
                    @error('product_evidence')
                    <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <label>Foto TTD Penerimaan Produk</label>
            <div class="col-md-12 my-2">
                <img id="preview-signature_evidence" src="{{ $evidence ? url('/upload/admin/delivery/signature/', $evidence->signature_evidence) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
            </div>
            <div class="col-md">
                <div class="form-group">
                    <input type="file" class="form-control @error('signature_evidence') is-invalid @enderror" name="signature_evidence" value="{{ old('signature_evidence') }}" id="signature_evidence" required>
                    @error('signature_evidence')
                    <small class="invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="border-bottom py-3">
            <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Simpan</button>
        </div>
    </form>
</div>


<script>
    $(document).ready(function() {
        // for display myimg
        $('#product_evidence').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-product_evidence').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
    $(document).ready(function() {
        // for display myimg
        $('#signature_evidence').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-signature_evidence').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>