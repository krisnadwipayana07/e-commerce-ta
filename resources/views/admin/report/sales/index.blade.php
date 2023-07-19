@extends('layouts.admin')

@section('page-title')
Halaman Laporan Penjualan
@endsection

@section('page-parent-route')
Laporan Penjualan
@endsection

@section('page-active-route')
Index
@endsection

@section('page-content-title')
Laporan Penjualan
@endsection

@section('page-content-desc')
Halaman untuk mengatur Laporan Penjualan
@endsection

@section('page-content-body')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.sales.report.print') }}" class="btn btn-success">Cetak Laporan</a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Name Pelanggan</th>
                    <th>Nama Barang</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
    $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.sales.report.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'customer_name',
                name: 'customer_name'
            },
            {
                data: 'property_name',
                name: 'property_name'
            },
            {
                data: 'total_payment',
                name: 'total_payment'
            },
            {
                data: 'payment_method',
                name: 'payment_method'
            },
        ],
        order: [
            [0, "code"]
        ]
    });
</script>
@endsection