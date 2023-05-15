<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Bali Artha Jaya</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('admin/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('admin/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

    <link href="{{ asset('admin/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/select2-custom.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">

    @yield('inject-head')
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                {{-- <img src="{{ url('admin/assets/img/logo.png') }}" alt=""> --}}
                <span class="d-none d-lg-block">Bali Artha Jaya</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->


                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="{{ url('admin/assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                        @if (auth()->guard('admin')->check())
                            @php
                                $username = auth()
                                    ->guard('admin')
                                    ->user()->username;
                            @endphp
                        @endif

                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ $username }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('auth.logout.admin') }}">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    {{-- ! ini mengatur sidebar admin --}}
    <!-- ======= Sidebar ======= -->
    @if (auth()->guard('admin')->check())
        @if (auth()->guard('admin')->user()->role == 'SUPERADMIN')
            @include('admin.components.superadmin_sidebar')
        @else
            @include('admin.components.admin_sidebar')
        @endif
    @endif

    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1 class="my-3">
                @yield('page-title')
            </h1>
            {{-- <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        @yield('page-parent-route')
                    </li>
                    <li class="breadcrumb-item active">
                        @yield('page-active-route')
                    </li>
                </ol>
            </nav> --}}
            @include('result')
            @yield('page-back-button')
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <h5>
                                            @yield('page-content-title')
                                        </h5>
                                        <p class="text-muted small">
                                            @yield('page-content-desc')
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-12 text-end">
                                        @yield('page-action-button')
                                    </div>
                                </div>
                            </div>
                            @yield('page-content-body')
                            {{-- <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Position</th>
                            <th scope="col">Age</th>
                            <th scope="col">Start Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Brandon Jacob</td>
                            <td>Designer</td>
                            <td>28</td>
                            <td>2016-05-25</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Bridie Kessler</td>
                            <td>Developer</td>
                            <td>35</td>
                            <td>2014-12-05</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Ashleigh Langosh</td>
                            <td>Finance</td>
                            <td>45</td>
                            <td>2011-08-12</td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Angus Grady</td>
                            <td>HR</td>
                            <td>34</td>
                            <td>2012-06-11</td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Raheem Lehner</td>
                            <td>Dynamic Division Officer</td>
                            <td>47</td>
                            <td>2011-04-19</td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows --> --}}

                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MAIN MODAL --}}
        <div class="modal fade" id="penuliskode-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penuliskode-modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="penuliskode-modal-body">
                        ...
                    </div>
                </div>
            </div>
        </div>

        {{-- END MAIN MODAL --}}

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright All Rights Reserved
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('admin/assets/vendor/jquery/jquery.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/vendor/apexcharts/apexcharts.min.js') }}"></script> --}}
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/vendor/chart.js/chart.min.js') }}"></script>
  <script src="{{ asset('admin/assets/vendor/echarts/echarts.min.js') }}"></script> --}}
    <script src="{{ asset('admin/assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/vendor/tinymce/tinymce.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/assets/vendor/php-email-form/validate.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>

    {{-- input spinner --}}
    <script src="{{ asset('admin/assets/js/bootstrap-input-spinner.js') }}"></script>

    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select-picker').select2({});
        });

        function penuliskode_modal(title, target) {
            $('#penuliskode-modal-title').html(title);
            $.get(target, function(result) {
                $('#penuliskode-modal-body').html(result);
                $('#penuliskode-modal').modal('show');
            }).fail(function(e) {
                alert("Terjadi kesalahan tidak terduga!");
                console.log(e);
            });
        }

        function delete_data(text, target, refresh) {
            Swal.fire({
                title: "Delete Data?",
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: target,
                        method: "POST",
                        data: '_method=DELETE&_token={{ csrf_token() }}',
                        success: function(data) {
                            if (data.status == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.text
                                })
                                window.location.href = refresh;
                            } else {
                                console.log(data);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Whoops!',
                                    text: data.text
                                })
                            }
                        },
                        error: function(data) {
                            console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: 'Whoops!',
                                text: 'Terjadi kesalahan.'
                            })
                        }
                    });
                }
            })
        }
    </script>

    @yield('page-js')

</body>

</html>
