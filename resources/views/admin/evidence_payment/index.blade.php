@extends('layouts.admin')

@section('page-title')
    Approved Payment Customer
@endsection

@section('page-parent-route')
    Approved Payment Customer
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-content-title')
    Approved Payment Customer
@endsection

@section('page-content-desc')
    Page to manage Approved Payment Customer
@endsection

@section('page-content-body')
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <th>#</th>
                        <th>Code</th>
                        <th>Phone Number</th>
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
            ajax: "{{ route('admin.evidence_payment.index') }}",
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
                    data: 'account_number',
                    name: 'account_number'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [[ 0, "code" ]]
        });
    </script>
@endsection
