<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Kode Transaksi</h6>{{ $data->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nama Penerima</h6>{{ $data->recipient_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Alamat Pengiriman</h6>{{ $data->deliver_to }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Nomber Telepon</h6>{{ $data->account_number }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Detail Transaksi</h6>
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

<div class="form-group py-3">
    <label>Status Pengiriman</label>
    <form action="{{ route('admin.evidence_payment.notify_user', $data->id) }}" method="POST">
        @csrf
        <input type="hidden" name="transaction_id" value="{{ $data->id }}">
        <div class="pb-1 w-25">
            <select class="form-select" aria-label="Default select example" name="type" required>
                <option selected>Pilih tipe pesan</option>
                @if ($data->status === "pending")
                <option value="Transaction - Warning">Perbaikan</option>
                @endif
                <option value="Transaction - Notification">Notifikasi Transaksi</option>
            </select>
        </div>
        <div class="d-flex">
            <input type="text" name="message" class="form-control" placeholder="Pesan (isi jika data pelanggan tidak sesuai)">
            <button type="submit" class="btn btn-primary">Kirim Pesan Notifikasi</button>
        </div>
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
        @if ($data->status === "pending")
        <form method="POST" action="{{ route('admin.evidence_payment.approve', $data->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="{{ $stt }}">
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Terima</button>
            </div>
        </form>
        @endif
    </div>
    <div class="col-md-2">
        @if ($data->status === "pending")
        <form method="POST" action="{{ route('admin.evidence_payment.reject', $data->id) }}">
            @csrf
            @method('DELETE')
            <div class="border-bottom py-3">
                <button type="submit" class="btn btn-danger"><i class="fa fa-fw fa-paper-plane me-1"></i>Tolak</button>
            </div>
        </form>
        @endif
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
