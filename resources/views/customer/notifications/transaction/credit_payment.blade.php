<form method="POST" action="{{ route('customer.credit.payment.store', $transaction->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Kode Transaksi</h6>{{ $transaction->code }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Bayar Perbulan</h6>{{ format_rupiah($transaction->payment_credit) }}
    </div>
    @if ($overPrice != 0)
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Denda</h6>{{ format_rupiah($overPrice) }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Total</h6>{{ format_rupiah($transaction->payment_credit + $overPrice) }}
    </div>
    @endif
    <div class="row mb-3">
        <div class="col-md">
            <div class="form-group">
            <label>Bukti Pembayaran</label>
                <input type="file" class="form-control @error('myimg') is-invalid @enderror" name="myimg" value="{{ old('myimg') }}" id="myimg">
                @error('myimg')<small class="invalid-feedback">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="col-md-12 my-2">
            <img id="preview-myimg" src="https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png" style="height: 300px;">
        </div>
    </div>
    <button type="submit" class="btn btn-success">Kirim</button>
</form>
<script>
    $('#myimg').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-myimg').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
</script>
