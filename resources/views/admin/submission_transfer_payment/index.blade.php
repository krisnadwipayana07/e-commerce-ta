@extends('layouts.admin')

@section('page-title')
Pembayaran Transfer Bank
@endsection

@section('page-parent-route')
Pembayaran Transfer Bank
@endsection

@section('page-active-route')
Index
@endsection

@section('page-content-title')
Pembayaran Transfer Bank
@endsection

@section('page-content-desc')
Halaman untuk mengatur Pembayaran Transfer Bank
@endsection

@section('page-content-body')
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Transaksi</th>
                    <th>Status</th>
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
        ajax: "{{ route('admin.submission.transfer.payment.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'customer_name',
                name: 'customer_name'
            },
            {
                data: 'transaction_date',
                name: 'transaction_date'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
</script>
@endsection