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
    <h5 class="fw-bold text-center">Detail Transaksi</h6>
        <div class="border-bottom py-3">
            <h6 class="font-weight-bold">Jenis Pembayaran</h6>{{ $data->category_payment->name }}
        </div>
        <h6 class="font-weight-bold my-3">Product</h6>
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
<div class="my-5">
    <p>Foto Penerimaan Produk</p>
    <img id="preview-product_evidence"
        src="{{ $evidence ? url('/upload/admin/delivery/product/', $evidence->product_evidence) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
        alt="preview image" style="height: 50vh;">

</div>
<div class="my-5">
    <p>Foto TTD Penerimaan Produk</p>
    <img id="preview-signature_evidence"
        src="{{ $evidence ? url('/upload/admin/delivery/signature/', $evidence->signature_evidence) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
        alt="preview image" style="height: 50vh;">
</div>
<div class="py-3">
    <label>Status Pesanan</label>
    {{-- {{$data->customer_id}} --}}
    <div class="form-group">
        <form action="{{ route('admin.delivery.change_status', $data->id) }}" method="POST">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $data->id }}">
            <input type="hidden" name="customer_id" value="{{ $data->customer_id }}">
            <input type="hidden" name="payment_type" value="{{ $data->category_payment->name }}">
            <div class="pb-1 d-flex gap-2">
                <select aria-label="Default select example" name="status" required id="payment_status" onchange="val()"
                    class="form-select w-50">
                    <option value="">Pilih Status Pengiriman</option>
                    <option value="Order Received" {{ $status == 'Order Received' ? 'selected' : '' }}>Pesanan Dibuat
                    <option value="In Packing" {{ $status == 'In Packing' ? 'selected' : '' }}>Pesanan Sedang Dikemas
                    </option>
                    <option value="In Transit" {{ $status == 'In Transit' ? 'selected' : '' }}>Pesananan Dalam
                        Pengiriman
                    </option>
                    <option value="Delivered" {{ $status == 'Delivered' ? 'selected' : '' }}>Pesanan Telah Diterima
                    </option>
                </select>
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Simpan
                    Status</button>
            </div>

        </form>
    </div>
    <form action="{{ route('admin.delivery.send_notifications') }}" method="POST">
        <div class="my-3 d-flex gap-2 message_confirmation">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $data->id }}">
            <input type="hidden" name="customer_id" value="{{ $data->customer->id }}">
            <input type="hidden" name="type" value="Delivery - Notification">
            <input type="text" name="message" placeholder="Informasi Pertanyaan Hari pengiriman"
                class="form-control">
            <button type="submit" class="btn btn-primary">Kirim Pesan Notifikasi</button>
        </div>
    </form>
    @foreach ($notifications as $idx => $notif)
        @if ($idx === 0)
            <h5 class="text-center">Reply Message</h5>
        @endif
        <div class="my-2">
            <div class="mb-3">
                <label for="adminMessage" class="form-label">Admin</label>
                <input type="text" id="adminMessage" class="form-control" placeholder="Disabled input" value="{{ $notif->message }}">
            </div>
            <div class="mb-3">
                <label for="customerReply" class="form-label">Customer</label>
                <input type="text" id="customerReply" class="form-control" placeholder="Disabled input" value="{{ $notif->reply }}">
            </div>
            <div>{{ $notif->message }} </div>
            <div> Balasan : {{ $notif->reply }} </div>
        </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        message_confirmation.style.display = 'none';
    });
</script>
{{-- <div class="border-bottom py-3">
    <h6 class="font-weight-bold">Created</h6>{{ format_datetime($data->created_at) }}
</div>
<div class="pt-3">
    <h6 class="font-weight-bold">Updated</h6>{{ format_datetime($data->updated_at) }}
</div> --}}
