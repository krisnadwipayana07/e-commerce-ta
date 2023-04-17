@extends('layouts.admin')

@section('page-title')
    Sales Report
@endsection

@section('page-parent-route')
    Sales Report
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-content-title')
    Sales Report
@endsection

@section('page-content-desc')
    Page to manage Sales Report
@endsection

@section('page-content-body')
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.sales.report.print') }}" class="btn btn-success">Print Report</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Property Name</th>
                        <th>Total Price</th>
                        <th>Payment Method</th>
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
            columns: [
                {
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
            order: [[ 0, "code" ]]
        });
    </script>
@endsection
