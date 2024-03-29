@extends('landing.landing')

@section('inject-head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
@endsection

@section('content')
<div class="news">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Produk</h2>
                </div>
            </div>
        </div>
        @php
        $delivery_fee = 0;
        $total = 0;
        @endphp
        <div class="row">
            <div class="col-md-12 margin_top40">
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-2">
                        <img src="{{ asset('upload/admin/property/' . $property->img) }}" alt="{{ asset($property->name) }}" />
                    </div>
                    <div class="col-md-6">
                        <div class="my-3">
                            @php
                            $total += $property->price * $quantity;
                            @endphp
                            <b>{{ $property->name }}</b>
                            <br><b style="color:black">Jumlah&emsp;: {{ $quantity }}x</b>
                            <br><b style="color:black">Harga&emsp; : {{ format_rupiah($property->price) }}</b>
                            <br>
                            <h5> <b style="color:brown">Total&emsp;: {{ format_rupiah($property->price * $quantity) }}
                            </h5></b>
                            <div class="row">
                                <div class="col-md-1">
                                    <form class="mt-2">
                                        <a href="{{ route('customer.checkout.single.edit.quantity', ['property_id' => $property->id, 'quantity' => $quantity]) }}" class="btn btn-warning"><i class="fa fa-solid fa-pencil"></i></a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Transaksi</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('customer.checkout.single.store') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="quantity_property" value="{{ $quantity }}">
                    @csrf
                    <div class="form-group">
                        <label>Jenis Pembayaran</label>
                        <select name="category_payment_id" class="select-picker form-control @error('category_payment_id') is-invalid @enderror" style="width:100%;" id="payment">
                            <option value="">--PILIH--</option>
                            @foreach ($payments as $payment)
                            @if (
                            ($payment->name != 'Kredit' && $payment->name != 'Credit') ||
                            auth()->guard('customer')->user()->allow_credit)
                            <option value="{{ $payment->id }}" data-val="{{ $payment->name }}" {{ old('category_payment_id') == $payment->id ? 'selected' : '' }}>
                                {{ $payment->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <select name="province" class="select-picker form-control @error('province') is-invalid @enderror" style="width:100%;" id="province">>
                            <option value="">--PILIH--</option>
                            @foreach ($list_province as $province)
                            <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
                                {{ $province }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Penerima</label>
                        <input type="text" name="recipient_name" class="form-control" value="{{ auth()->guard('customer')->user()->name }}" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="deliver_to" id="" cols="30" rows="10" class="form-control" placeholder="Jawaban Anda">{{ auth()->guard('customer')->user()->address }}</textarea>
                        <small style="color: red;">*Wajib mengisikan titik koordinat pada peta</small>
                        <div id="map" class="my-3" style="height: 280px;"></div>
                        @error('lat')
                        <small style="color:red;">{{ $message }}</small>
                        @enderror
                        <input type="hidden" id="latitude" name="lat" required>
                        <input type="hidden" id="longitude" name="lng" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" name="account_number" class="form-control" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group evidence_payment">
                        <label>Bukti Pembayaran</label>
                        <input type="file" name="myimg" class="form-control">
                        <small style="color: red;">*Only transfer payment</small>
                    </div>
                    <div class="form-group credit_period">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control credit_period_input" name="full_name" id="full_name" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="ktp_name" class="form-label">Nama Sesuai KTP</label>
                        <input type="text" class="form-control credit_period_input" name="ktp_name" id="ktp_name" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="ktp_number" class="form-label">Nomor KTP</label>
                        <input type="number" class="form-control credit_period_input" name="ktp_number" id="ktp_number" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="ktp_address" class="form-label">Alamat Sesuai KTP</label>
                        <textarea name="ktp_address" id="ktp_address" cols="30" rows="10" placeholder="Jawaban Anda" class="form-control credit_period_input"></textarea>
                    </div>
                    <div class="form-group credit_period">
                        <label for="nickname" class="form-label">Nama Panggilan</label>
                        <input type="text" class="form-control credit_period_input" name="nickname" id="nickname" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="mother_name" class="form-label">Nama Ibu Kandung</label>
                        <input type="text" class="form-control credit_period_input" name="mother_name" id="mother_name" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="post_code" class="form-label">Kode Pos</label>
                        <input type="number" class="form-control credit_period_input" name="post_code" id="post_code" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control credit_period_input" name="birth_place" id="birth_place" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control credit_period_input w-25" name="birth_date" id="birth_date">
                    </div>
                    <div class="form-group credit_period">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="form-control credit_period_input">
                            <option value="">--PILIH--</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="home_state" class="form-label">Status Rumah</label>
                        <select name="home_state" id="home_state" class="form-control credit_period_input">
                            <option value="">--PILIH--</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Milik Keluarga">Milik Keluarga</option>
                            <option value="Sewa Kontrak">Sewa Kontrak</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="long_occupied" class="form-label">Lama Di Tempati</label>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control credit_period_input w-25" name="long_occupied" id="long_occupied">&emsp;
                            <select name="year_or_month_occupied" id="year_or_month_occupied" class="form-control credit_period_input">
                                <option value="">--PILIH--</option>
                                <option value="Bulan">Bulan</option>
                                <option value="Tahun">Tahun</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group credit_period">
                        <label for="education" class="form-label">Pendidikan</label>
                        <select name="education" id="education" class="form-control credit_period_input">
                            <option value="">--PILIH--</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="marital_status" class="form-label">Status</label>
                        <select name="marital_status" id="marital_status" class="form-control credit_period_input">
                            <option value="">--PILIH--</option>
                            <option value="Sudah Menikah">Sudah Menikah</option>
                            <option value="Belum Menikah">Belum Menikah</option>
                            <option value="Janda/Duda">Janda/Duda</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="jobs" class="form-label">Pekerjaan</label>
                        <select name="jobs" id="jobs" class="form-control credit_period_input">
                            <option value="">--PILIH--</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Pegawai Swasta">Pegawai Swasta</option>
                            <option value="Pegawai Negri Sipil">Pegawai Negri Sipil</option>
                            <option value="Pegawai BUMN">Pegawai BUMN</option>
                            <option value="TNI/POLRI">TNI/POLRI</option>
                            <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="company_name" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control credit_period_input" name="company_name" id="company_name" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="company_address" class="form-label">Alamat Perusahaan</label>
                        <textarea name="company_address" id="company_address" cols="30" rows="10" class="form-control credit_period_input" placeholder="Jawaban Anda"></textarea>
                    </div>
                    <div class="form-group credit_period">
                        <label for="company_phone" class="form-label">No Telepon Perusahaan</label>
                        <input type="text" class="form-control credit_period_input" name="company_phone" id="company_phone" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="length_of_work" class="form-label">Lama Bekerja</label>
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control w-25 credit_period_input" name="length_of_work" id="length_of_work">&emsp;
                            <select name="year_or_month_work" id="year_or_month_work" class="form-control credit_period_input">
                                <option value="">--PILIH--</option>
                                <option value="Bulan">Bulan</option>
                                <option value="Tahun">Tahun</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group credit_period">
                        <label for="income_amount" class="form-label">Jumlah Penghasilan</label>
                        <select name="income_amount" id="income_amount" class="form-control">
                            <option value="">--PILIH--</option>
                            <option value=500000>
                                < 500 Ribu</option>
                            <option value=2000000>1 - 2 juta</option>
                            <option value=4000000>3 - 4 juta</option>
                            <option value=5000000>>5 juta</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="extra_income" class="form-label">Penghasilan Tambahan</label>
                        <select name="extra_income" id="extra_income" class="form-control">
                            <option value="">--PILIH--</option>
                            <option value=500000>
                                < 500 Ribu</option>
                            <option value=2000000>1 - 2 juta</option>
                            <option value=4000000>3 - 4 juta</option>
                            <option value=5000000>>5 juta</option>
                        </select>
                        <small style="color: red;">*Pilih jika memiliki penghasilan tambahan</small>
                    </div>
                    <div class="form-group credit_period">
                        <label for="spending" class="form-label">Pengeluaran</label>
                        <select name="spending" id="spending" class="form-control">
                            <option value="">--PILIH--</option>
                            <option value=500000>
                                < 500 Ribu</option>
                            <option value=2000000>1 - 2 juta</option>
                            <option value=4000000>3 - 4 juta</option>
                            <option value=5000000>>5 juta</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="residual_income" class="form-label">Sisa Penghasilan</label>
                        <select name="residual_income" id="residual_income" class="form-control">
                            <option value="">--PILIH--</option>
                            <option value=500000>
                                < 500 Ribu</option>
                            <option value=2000000>1 - 2 juta</option>
                            <option value=4000000>3 - 4 juta</option>
                            <option value=5000000>>5 juta</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="transportation_type" class="form-label">Jenis Kendaraan</label>
                        <select name="transportation_type" id="transportation_type" class="form-control">
                            <option value="">--PILIH--</option>
                            <option value="Sepeda Motor">Sepeda Motor</option>
                            <option value="Mobil">Mobil</option>
                            <option value="Truk">Truk</option>
                            <option value="Bus">Bus</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="transportation_brand" class="form-label">Merek</label>
                        <input type="text" class="form-control credit_period_input" name="transportation_brand" id="transportation_brand" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="year_of_purchase" class="form-label">Tahun Pembelian</label>
                        <input type="number" class="form-control credit_period_input" name="year_of_purchase" id="year_of_purchase" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="police_number" class="form-label">Nomor Polisi</label>
                        <input type="text" class="form-control credit_period_input" name="police_number" id="police_number" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="transportation_color" class="form-label">Warna</label>
                        <input type="text" class="form-control credit_period_input" name="transportation_color" id="transportation_color" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="bpkb_number" class="form-label">Nomor BPKB</label>
                        <input type="text" class="form-control credit_period_input" name="bpkb_number" id="bpkb_number" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="rekening_number" class="form-label">Nomor Rekening</label>
                        <input type="text" class="form-control credit_period_input" name="rekening_number" id="rekening_number" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="bank" class="form-label">Nama Bank</label>
                        <select name="bank" id="bank" class="form-control">
                            <option value="">--PILIH--</option>
                            <option value="BRI">BRI</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="Mandiri">Mandiri</option>
                        </select>
                    </div>
                    <div class="form-group credit_period">
                        <label for="owner_rekening" class="form-label">Pemilik Rekening</label>
                        <input type="text" class="form-control credit_period_input" name="owner_rekening" id="owner_rekening" placeholder="Jawaban Anda">
                    </div>
                    <div class="form-group credit_period">
                        <label for="house_image" class="form-label">Foto Rumah</label>
                        <input type="file" class="form-control credit_period_input" name="house_image" id="house_image">
                    </div>
                    <div class="form-group credit_period">
                        <label for="ktp" class="form-label">Foto KTP</label>
                        <input type="file" class="form-control credit_period_input" name="ktp" id="ktp">
                    </div>
                    <div class="form-group credit_period">
                        <label for="photo" class="form-label">Foto Selfie</label>
                        <input type="file" class="form-control credit_period_input" name="photo" id="photo">
                    </div>
                    <div class="form-group credit_period">
                        <label for="salary_slip" class="form-label">Foto Slip Gaji</label>
                        <input type="file" class="form-control credit_period_input" name="salary_slip" id="salary_slip">
                    </div>
                    <div class="form-group credit_period">
                        <label for="transportation_image" class="form-label">Foto Kendaraan</label>
                        <input type="file" class="form-control credit_period_input" name="transportation_image" id="transportation_image">
                    </div>
                    <div class="form-group credit_period">
                        <label for="rekening_book_image" class="form-label">Foto Buku Rekening</label>
                        <input type="file" class="form-control credit_period_input" name="rekening_book_image" id="rekening_book_image">
                    </div>
                    <div class="form-group credit_period">
                        <label>Periode Kredit</label>
                        <select name="credit_period" class="select-picker form-control credit_period_input @error('credit_period') is-invalid @enderror credit_period" id="credit_period" style="width:100%;">
                            <option value="3" class="3months">3 Bulan (Bunga 1%)</option>
                            <option value="6" class="6months">6 Bulan (Bunga 2%)</option>
                            <option value="12" class="12months">12 Bulan (Bunga 2,5%)</option>
                        </select>
                        <small style="color: red;">*Pilih Periode Kredit</small>
                    </div>

                    <div class="form-group total_field " style="display: none;">
                        <input id="delivery_fee" value="{{ $delivery_fee }}" name="delivery_fee">
                        <input name="total" id="total_payment" value="{{ $total }}">
                    </div>

                    <div class="form-check credit_period">
                        <input class="form-check-input" type="checkbox" value="" id="checkbox-agree">
                        <label class="form-check-label" for="checkbox-agree">
                            Semua informasi dalam formulir ini adalah lengka dan benar. Dengan mengisi formulir ini,
                            Saya memnerikan kuasa kepada BALI ARTHA JAYA untuk memeriksa semua data dengan cara
                            bagaimanapun yang layak menurut BALI ARTHA JAYA.
                        </label>
                    </div>

                    <div class="titlepage">
                        <h1> </h1>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <p class="fw-bold">Ongkos Kirim</p>
                            <p class="fw-bold">Total</p>
                        </div>
                        <div class="col-md total_show">
                            <p class="fw-bold">: {{IDRConvert($delivery_fee)}} </p>
                            <p class="fw-bold">: {{IDRConvert($total)}} </p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block btn-success" id="btn-submit">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    <div class="total_show_temp">
    </div>
    <div class="total_field_temp" style="display: none;">
        <input id="delivery_fee" name="delivery_fee" value="{{ $delivery_fee }}">
        <input id="total_payment" name="total" value="{{ $total }}">
    </div>
    <!-- end news section -->
    @endsection

    @section('page-js')
    <script>
        $(document).ready(function() {
            $('.evidence_payment').hide();
            $('.credit_period').hide();
        });

        $('#province').on('change', function() {
            var val = $(this).find(":selected").val();
            var IDRConvert = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            })
            var payment = $('#payment').find(":selected").text().trim();
            var credit_period = $('#credit_period').find(":selected").val();
            var delivery_fee = parseInt($("#delivery_fee").val());
            var total = parseInt($("#total_payment").val());
            var totalShow = $(".total_show_temp").html();
            var html = $(".total_field_temp").html();
            // window.console&&console.log(credit_period);

            if (val.trim() == "Bali") {
                delivery_fee = 30000;
            } else if (val.trim() == "") {
                delivery_fee = 0;
            } else {
                delivery_fee = 50000;
            }

            var n_total = total + delivery_fee;
            if (payment == "Kredit" || payment == "Credit") {
                var dp = Math.round((Math.round(n_total * 30 / 100)) / 1000) * 1000;
            }

            html = "<input name='total' value=" + n_total + ">";
            html += "<input name='delivery_fee' value=" + delivery_fee + ">";

            n_total = IDRConvert.format(n_total);
            delivery_fee = IDRConvert.format(delivery_fee);

            totalShow = "<p class='fw-bold'> :" + delivery_fee + " </p>"
            totalShow += "<p class='fw-bold'> :" + n_total + " </p>"

            $('.total_show').html(totalShow);
            $('.total_field').html(html);
        });

        $('#payment').on('change', function() {
            var val = $(this).find(":selected").text();
            var credit_p = 3;
            $(".credit_period").val(3).change();
            var total = parseInt($("#total_payment").val());
            var delivery_fee = 0;
            var html = $(".total_field_temp").html();
            var bunga = 1 / 100;
            var n_total = total + (total * bunga) + delivery_fee;
            var dp = Math.round((Math.round(n_total * 30 / 100)) / 1000) * 1000;

            var province = $('#province').find(":selected").val().trim();

            if (province == "Bali") {
                delivery_fee = 30000;
            } else if (province == "") {
                delivery_fee = 0;
            } else {
                delivery_fee = 50000;
            }

            html = "<input name='total' value=" + n_total + ">";
            html += "<input name='delivery_fee' value=" + delivery_fee + ">";

            if (val.trim() == "Cash" || val.trim == "Cash On Delivery") {
                $('.evidence_payment').hide();
                $('.credit_period').hide();
                if ($('#btn-submit').hasClass('disabled')) {
                    $('#btn-submit').removeClass('disabled');
                }
            } else if (val.trim() == 'Kredit' || val.trim() == "Credit") {
                $('.evidence_payment').hide();
                $('.credit_period').show();
                $('#btn-submit').addClass("disabled");
                $('#checkbox-agree').attr('checked', false);
                html += "<input name='down_payment' value=" + (dp) + ">";
                html += "<input name='payment_credit' value=" + ((n_total - dp) / 3) + ">";
            } else {
                $('.evidence_payment').hide();
                $('.credit_period').hide();
                if ($('#btn-submit').hasClass('disabled')) {
                    $('#btn-submit').removeClass('disabled');
                }
            }
            window.console && console.log(credit_period);
            $('.total_field').html(html);
        });

        $('#credit_period').on('change', function() {
            var val = $(this).find(":selected").val();
            var total = parseInt($("#total_payment").val());
            var delivery_fee = 0;
            var province = $('#province').find(":selected").val().trim();

            var html = $(".total_field_temp").html();
            var bunga = 1 / 100;
            if (val == 6) {
                bunga = 2 / 100;
            }
            if (val == 12) {
                bunga = 2.5 / 100;
            }

            if (province == "Bali") {
                delivery_fee = 30000;
            } else if (province == "") {
                delivery_fee = 0;
            } else {
                delivery_fee = 50000;
            }
            var n_total = total + (total * bunga) + delivery_fee;
            var dp = Math.round((Math.round(n_total * 30 / 100)) / 1000) * 1000;

            html = "<input name='total' value=" + n_total + ">";
            html += "<input name='delivery_fee' value=" + delivery_fee + ">";
            html += "<input name='down_payment' value=" + (dp) + ">";
            html += "<input name='payment_credit' value=" + ((n_total - dp) / val) + ">";

            $('.total_field').html(html);
        });

        $(".credit_period_input").on('change', function() {
            $('#checkbox-agree').removeAttr('checked');
        })
        $('#checkbox-agree').on('click', function() {
            var inputs = $(".credit_period_input");
            var check = true;
            for (var i = 0; i < inputs.length; i++) {
                if ($(inputs[i]).val() == "" || $(inputs[i]).val() == null) {
                    check = false;
                }
            }
            if (check == false) {
                alert("Lengkapi data terlebih dahulu");
                $('#checkbox-agree').removeAttr('checked');
                if (!$('#btn-submit').hasClass('disabled')) {
                    $('#btn-submit').addClass('disabled');
                }
            } else {
                if ($('#btn-submit').hasClass('disabled')) {
                    $('#btn-submit').removeClass('disabled');
                }
            }
        });

        function format_rupiah(number) {
            var bilangan = Math.ceil(number);
            console.log(bilangan)

            var number_string = bilangan.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return "IDR " + rupiah;
        }

        // Init map
        var map = L.map('map').setView([-2.6, 120.16], 5);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Keep track of the currently displayed marker
        var displayedMarker = null;

        // Map on click event listener
        map.on('click', function(e) {
            // Get latitude longitude
            var latitude = e.latlng.lat;
            var longitude = e.latlng.lng;

            // Remove the displayed marker (if any)
            if (displayedMarker) {
                displayedMarker.remove();
            }

            // Set input value
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);

            // Add marker to the map
            displayedMarker = L.marker([latitude, longitude]).addTo(map);
        });
    </script>
    @endsection