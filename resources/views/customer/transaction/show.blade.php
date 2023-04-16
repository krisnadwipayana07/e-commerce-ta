<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Code</h6>{{ $data->code }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Recipient Name</h6>{{ $data->recipient_name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Deliver To</h6>{{ $data->deliver_to }}
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
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Payment Method</h6>{{ $data->category_payment->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Status</h6>{{ returnStatusOrder($data->status) }}
</div>

<form method="POST" action="{{ route('customer.transaction.update', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Image</h6>
        <input type="file" class="mb-3 form-control @error('myimg') is-invalid @enderror" name="myimg" value="{{ old('myimg') }}" id="myimg">
        @error('myimg')<small class="invalid-feedback">{{ $message }}</small>@enderror
        <img id="preview-myimg" src="{{ ($data->img) ? url('/upload/admin/transaction/', $data->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
    </div>
    <div class="border-bottom py-3">
        <button type="reset" class="btn btn-danger"><i class="fa fa-fw fa-undo me-1"></i>Reset</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-paper-plane me-1"></i>Update</button>
    </div>
</form>
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
