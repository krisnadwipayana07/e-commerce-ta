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
    {{-- {{$data->customer_id}} --}}
    <form action="{{ route('admin.delivery.change_status', $data->id) }}" method="POST">
        @csrf
        <input type="hidden" name="transaction_id" value="{{ $data->id }}">
        <input type="hidden" name="customer_id" value="{{ $data->customer_id }}">
        <div class="pb-1">
            <select class="form-select" aria-label="Default select example" name="status" required>
                <option selected>Pilih Status Pengiriman</option>
                <option value="Order Received">Order Received</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>
        <div class="border-bottom py-3">
            <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Terima</button>
        </div>
    </form>
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
