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
@if ($data->category_payment->name == 'Kredit' || $data->category_payment->name == 'Credit')
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Periode Kredit</h6>{{ $data->credit_period }}x
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Bayar Per Bulan</h6>{{ format_rupiah($data->payment_credit) }}
    </div>
@endif
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Metode Pembayaran</h6>{{ $data->category_payment->name }}
</div>
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Tanggal Transaksi</h6>{{ $data->created_at }}
</div>
@if ($data->category_payment->name == 'Kredit' || $data->category_payment->name == 'Credit')
    @if ($data->is_dp_paid == 0 && $data->status == 'in_progress')
        <div class="border-bottom py-3">
            <h6 class="font-weight-bold">Batas Pembayaran DP</h6>{{ $data->due_date }}
        </div>
    @endif
@endif
{{-- @if ($data->category_payment->name != 'Cash' && $data->category_payment->name != 'Cash On Delivery' && $data->category_payment->name != 'Kredit' && $data->category_payment->name != 'Credit')
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Image</h6>
    <img id="preview-myimg" src="{{ ($data->img) ? url('/upload/evidence_payment/', $data->img) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}" alt="preview image" style="height: 300px;">
</div>
@endif --}}
@if ($data->category_payment->name == 'Kredit' || $data->category_payment->name == 'Credit')
    <h4 class="pt-5 fw-bold">Detail Data Pelanggan Kredit</h4>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nama Lengkap</h6>{{ $submission->full_name }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nama Panggilan</h6>{{ $submission->nickname }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nama Ibu Kandung</h6>{{ $submission->mother_name }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Kode Pos</h6>{{ $submission->post_code }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Tempat Lahir</h6>{{ $submission->birth_place }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Tanggal Lahit</h6>{{ $submission->birth_date }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Jenis Kelamin</h6>{{ $submission->gender }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Status Rumah</h6>{{ $submission->home_state }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Lama Ditempati</h6>{{ $submission->long_occupied }}
        {{ $submission->year_or_month_occupied }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Pendidikan</h6>{{ $submission->education }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Status</h6>{{ $submission->marital_status }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Pekerjaan</h6>{{ $submission->jobs }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nama Perusahaan</h6>{{ $submission->company_name }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Alamat Perusahaan</h6>{{ $submission->company_address }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nomer Telepon Perusahaan</h6>{{ $submission->company_phone }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Lama Bekerja</h6>{{ $submission->length_of_work }}
        {{ $submission->year_or_month_work }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Jumlah Penghasilan</h6>
        @if (@isset($payment_value[$submission->income_amount]))
            {{ $payment_value[$submission->income_amount] }}
        @else
            {{ $submission->income_amount }}
        @endif
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Penghasilan Tambahan</h6>
        @if (@isset($payment_value[$submission->extra_income]))
            {{ $payment_value[$submission->extra_income] }}
        @else
            {{ $submission->extra_income }}
        @endif
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Pengeluaran</h6>
        @if (@isset($payment_value[$submission->spending]))
            {{ $payment_value[$submission->spending] }}
        @else
            {{ $submission->spending }}
        @endif
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Sisa Penghasilan</h6>
        @if (@isset($payment_value[$submission->residual_income]))
            {{ $payment_value[$submission->residual_income] }}
        @else
            {{ $submission->residual_income }}
        @endif
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Jenis Kendaraan</h6>{{ $submission->transportation_type }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Merek Kendaraan</h6>{{ $submission->transportation_brand }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Tahun Pembelian</h6>{{ $submission->year_of_purchase }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nomer Polisi</h6>{{ $submission->police_number }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Warna Kendaraan</h6>{{ $submission->transportation_color }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nomor BPKB</h6>{{ $submission->bpkb_number }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nomor Rekening</h6>{{ $submission->rekening_number }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nama Bank </h6>{{ $submission->bank }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Pemilik Rekening</h6>{{ $submission->owner_rekening }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Nama Sesuai KTP</h6>{{ $submission->ktp_name }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">NomorKTP</h6>{{ $submission->ktp_number }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Alamat Sesuai KTP</h6>{{ $submission->ktp_address }}
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Foto KTP</h6>
        <a target="_blank"
            href="{{ $submission->ktp ? url('/upload/transaction/submission_credit/', $submission->ktp) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
            <img id="preview-ktp"
                src="{{ $submission->ktp ? url('/upload/transaction/submission_credit/', $submission->ktp) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                alt="preview image" style="height: 300px;">
        </a>
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Foto Slip Gaji</h6>
        <a target="_blank"
            href="{{ $submission->salary_slip ? url('/upload/transaction/submission_credit/', $submission->salary_slip) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
            <img id="preview-salary_slip"
                src="{{ $submission->salary_slip ? url('/upload/transaction/submission_credit/', $submission->salary_slip) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                alt="preview image" style="height: 300px;">
        </a>
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Foto Selfi</h6>
        <a target="_blank"
            href="{{ $submission->photo ? url('/upload/transaction/submission_credit/', $submission->photo) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
            <img id="preview-photo"
                src="{{ $submission->photo ? url('/upload/transaction/submission_credit/', $submission->photo) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                alt="preview image" style="height: 300px;">
        </a>
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Foto Rumah</h6>
        <a target="_blank"
            href="{{ $submission->house_image ? url('/upload/transaction/submission_credit/', $submission->house_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
            <img id="preview-photo"
                src="{{ $submission->house_image ? url('/upload/transaction/submission_credit/', $submission->house_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                alt="preview image" style="height: 300px;">
        </a>
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Foto Kendaraan</h6>
        <a target="_blank"
            href="{{ $submission->transportation_image ? url('/upload/transaction/submission_credit/', $submission->transportation_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
            <img id="preview-photo"
                src="{{ $submission->transportation_image ? url('/upload/transaction/submission_credit/', $submission->transportation_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                alt="preview image" style="height: 300px;">
        </a>
    </div>
    <div class="border-bottom py-3">
        <h6 class="font-weight-bold">Foto Buku Rekening</h6>
        <a target="_blank"
            href="{{ $submission->rekening_book_image ? url('/upload/transaction/submission_credit/', $submission->rekening_book_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}">
            <img id="preview-photo"
                src="{{ $submission->rekening_book_image ? url('/upload/transaction/submission_credit/', $submission->rekening_book_image) : 'https://cdn.pixabay.com/photo/2015/12/22/04/00/photo-1103595_960_720.png' }}"
                alt="preview image" style="height: 300px;">
        </a>
    </div>
@endif
<div class="border-bottom py-3">
    <h6 class="font-weight-bold">Alamat</h6>
    <div id="map" class="my-3" style="height: 280px;"></div>
    <input type="hidden" id="latitude" name="lat" value="{{ $data->latitude ?: '' }}">
    <input type="hidden" id="longitude" name="lng" value="{{ $data->longitude ?: '' }}">
</div>
<div class="form-group py-3">
    <label>Pesan Notifikasi ke Pengguna <small><em>(opsional)</em></small></label>
    <form action="{{ route('admin.evidence_payment.notify_user', $data->id) }}" method="POST">
        @csrf
        <input type="hidden" name="transaction_id" value="{{ $data->id }}">
        <div class="pb-1 w-25">
            <select class="form-select" aria-label="Default select example" name="type" required>
                <option selected>Pilih tipe pesan</option>
                @if ($data->status === 'pending')
                    <option value="Transaction - Warning">Perbaikan</option>
                @endif
                <option value="Transaction - Notification">Notifikasi Transaksi</option>
            </select>
        </div>
        <div class="d-flex">
            <input type="text" name="message" class="form-control"
                placeholder="Pesan (isi jika data pelanggan tidak sesuai)">
            <button type="submit" class="btn btn-primary">Kirim Pesan Notifikasi</button>
        </div>
    </form>
</div>
<div class="row">
    @if ($data->status != 'reject')
        @if ($data->status === 'pending')
            <div class="col-md-2">
                @php
                    $stt = 'in_progress';
                @endphp

                <form method="POST" action="{{ route('admin.evidence_payment.approve', $data->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $stt }}">
                    <div class="border-bottom py-3">
                        <button type="submit" class="btn btn-success"><i
                                class="fa fa-fw fa-paper-plane me-1"></i>Terima</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2">
                <form method="POST" action="{{ route('admin.evidence_payment.reject', $data->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="border-bottom py-3">
                        <button type="submit" class="btn btn-danger"><i
                                class="fa fa-fw fa-paper-plane me-1"></i>Tolak</button>
                    </div>
                </form>
            </div>
        @elseif (
            $data->category_payment->name == 'Kredit' ||
                ($data->category_payment->name == 'Credit' && $data->is_dp_paid == 0 && $data->status === 'is_progress'))
            <div class="col-md-2">
                <form method="POST" action="{{ route('admin.evidence_payment.reject', $data->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="border-bottom py-3">
                        <button type="submit" class="btn btn-danger"><i
                                class="fa fa-fw fa-paper-plane me-1"></i>Tolak</button>
                    </div>
                </form>
            </div>
        @endif
    @endif
</div>
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
