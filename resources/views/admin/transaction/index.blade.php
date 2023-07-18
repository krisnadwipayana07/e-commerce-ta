@extends('layouts.admin')

@section('page-title')
Halaman Transaksi
@endsection

@section('page-parent-route')
Transaksi
@endsection

@section('page-active-route')
Index
@endsection

@section('page-action-button')
{{-- <a href="{{ route("admin.transaction.create") }}" class="btn btn-sm btn-success"><i class="ri-add-circle-line"></i> Tambah Data</a> --}}
@endsection

@section('page-content-title')
Transaksi Data
@endsection

@section('page-content-desc')
Halaman untuk mengatur Transaksi Data
@endsection

@section('page-content-body')

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Telepon</th>
                    <th>Kode</th>
                    <th>Total Pembayara</th>
                    <th>Metode Pembayara</th>
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
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
        ajax: "{{ route('admin.transaction.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'customer_id',
                name: 'customer_id'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'total_payment',
                name: 'total_payment'
            },
            {
                data: 'category_payment_id',
                name: 'category_payment_id'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ],
        order: [
            [0, "created_at"]
        ]
    });
</script>
@endsection