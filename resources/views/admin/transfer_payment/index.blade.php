@extends('layouts.admin')

@section('page-title')
    Submission Credit Payment
@endsection

@section('page-parent-route')
    Submission Credit Payment
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-content-title')
    Submission Credit Payment
@endsection

@section('page-content-desc')
    Page to manage submission credit payment
@endsection

@section('page-content-body')
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                    <thead>
                        <th>#</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Due Date</th>
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
            ajax: "{{ route('admin.submission.credit.payment.index') }}",
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
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'due_date',
                    name: 'due_date'
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
