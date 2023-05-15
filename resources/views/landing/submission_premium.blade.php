@extends('landing.landing')

@section('content')
    <div class="news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Upgrade Akun Premium</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('customer.profile.submission_premium.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="ktp_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="ktp_name" id="ktp_name">
                        </div>
                        <div class="mb-3">
                            <label for="ktp_number" class="form-label">Nomor KTP</label>
                            <input type="text" class="form-control" name="ktp_number" id="ktp_number">
                        </div>
                        <div class="mb-3">
                            <label for="ktp_address" class="form-label">Alamat Sesuai KTP</label>
                            <textarea name="ktp_address" id="ktp_address" cols="30" rows="10" placeholder="KTP Address" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Gaji (Pengahasilan)</label>
                            <input type="number" name="salary" id="salary" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ktp" class="form-label">Foto KTP</label>
                            <input type="file" class="form-control" name="ktp" id="ktp">
                        </div>
                        <div class="mb-3">
                            <label for="salary_slip" class="form-label">Foto Slip Gaji</label>
                            <input type="file" class="form-control" name="salary_slip" id="salary_slip">
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto Selfi</label>
                            <input type="file" class="form-control" name="photo" id="photo">
                        </div>
                        <button type="submit" class="btn btn-success">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection