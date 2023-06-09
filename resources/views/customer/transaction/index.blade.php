@extends('layouts.customer')

@section('page-title')
    Transaction  Page
@endsection

@section('page-parent-route')
    Transaction 
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-action-button')
    <a href="{{ route("customer.transaction.create") }}" class="btn btn-sm btn-success"><i class="ri-add-circle-line"></i> Add Data</a>
@endsection

@section('page-content-title')
    Transaction Data
@endsection

@section('page-content-desc')
    Page to manage transaction data
@endsection

@section('page-content-body')

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <th>#</th>
                        <th>Code</th>
                        <th>Total Payment</th>
                        <th>Payment Method</th>
                        <th>Action</th>
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
            ajax: "{{ route('customer.transaction.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
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
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [[ 0, "created_at" ]]
        });

    </script>
@endsection
