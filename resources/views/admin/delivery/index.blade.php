@extends('layouts.admin')

@section('page-title')
    Delivery Product
@endsection

@section('page-parent-route')
    Delivery Product
@endsection

@section('page-active-route')
    Index
@endsection

@section('page-content-title')
    Delivery Product
@endsection

@section('page-content-desc')
    Page to manage Delivery Product
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
                        <th>Action</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.delivery.index') }}",
            columns: [{
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
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [0, "code"]
            ]
        });

        // Get the modal element
        var modal = document.getElementById("penuliskode-modal");

        // Attach an event listener to the modal's "show" event
        modal.addEventListener("show.bs.modal", function() {
            // Call your JavaScript function here
            console.log('test');
            showMap();
        });
    </script>
@endsection
