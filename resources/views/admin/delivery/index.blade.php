@extends('layouts.admin')

@section('page-title')
Halaman Pengiriman
@endsection

@section('page-parent-route')
Pengiriman
@endsection

@section('page-active-route')
Index
@endsection

@section('page-content-title')
Pengiriman
@endsection

@section('page-content-desc')
Halaman untuk mengatur Pengiriman
@endsection

@section('page-content-body')
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="datatables" width="100%" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>Kode</th>
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