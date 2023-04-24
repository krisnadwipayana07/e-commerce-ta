@extends('layouts.admin')

@section('inject-head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
    integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
    crossorigin=""/>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
    integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
    crossorigin=""></script>
@endsection

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
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
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

        // Init map
        function showMap() {
            var map = L.map('map').setView([-2.6, 120.16], 5);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            
            // Display marker
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();
            if (latitude != null && longitude != null) {
                console.log('a')
                $('#map').show();
                L.marker([latitude, longitude]).addTo(map);
            } else {
                console.log('b')
                ('#map').hide();
            }
        }

        // Get the modal element
        var modal = document.getElementById("penuliskode-modal");
        
        // Attach an event listener to the modal's "show" event
        modal.addEventListener("show.bs.modal", function () {
            // Call your JavaScript function here
            console.log('test');
            showMap();
        });
    </script>
@endsection
