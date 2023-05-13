<header>
    <!-- header inner -->
    <div class="header">
        {{--
        <div class="header_midil">
            <div class="container">
                <div class="row d_flex">
                    <div class="col-md-4">
                        <a class="logo blodark-2" href="#"><img src="{{ asset('1.jpg') }}" alt="{{ asset('1.jpg') }}"
                                height="42" width="42" /> Bali Artha Jaya</a>
                    </div>
                    <div class="col-md-4">
                        <ul class="right_icon ">
                        <li><a href="#"><img src="images/shopping.png" alt="#" /></a> </li>
                        </ul>
                    </div>
                    @if (!auth()->guard('customer')->user())
                    <div class="col-md-2">
                        <a href="{{ route('auth.register.register_customer') }}" class="order ">Register</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('auth.login.login_customer') }}" class="order ">Login</a>
                    </div>
                    @else
                    <div class="col-md-2">
                        <nav class="header-nav ms-auto">
                            <ul class="d-flex align-items-center">
                                <li class="nav-item dropdown pe-3">
                                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                                        data-bs-toggle="dropdown">
                                        <img src="{{ url('admin/assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle" style="max-width: 40px;">
                                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->guard('customer')->user()->username }}</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('customer.profile.index') }}">
                                                <i class="bi bi-box-arrow-right"></i>
                                                <span>Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="{{ route('auth.logout.customer') }}">
                                                <i class="bi bi-box-arrow-right"></i>
                                                <span>Sign Out</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        --}}
        <div class="header_bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <nav class="navigation navbar navbar-expand-md navbar-dark ">
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarsExample04">
                                <ul class="navbar-nav mr-auto">
                                    <li class="nav-item {{ set_active('landing.index') }}">
                                        <a class="nav-link" href="{{ route('landing.index') }}">Beranda</a>
                                    </li>
                                    <li class="nav-item {{ set_active('landing.product', 0) }}">
                                        {{-- <a class="nav-link" href="{{ route('landing.product') }}">Category Products</a> --}}
                                        {{-- ! --}}
                                        <div class="nav-item dropdown">
                                            <a href="#" class="nav-link dropdown-toggle"
                                                data-bs-toggle="dropdown">Kategori Produk</a>
                                            <div class="dropdown-menu m-0">
                                                @foreach ($header_category as $item)
                                                    <a href="{{ route('landing.product', $item->id) }}"
                                                        class="dropdown-item">{{ $item->name }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="https://wa.me/6282145105606">Hubungi Kami</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="search">
                                    <form action="{{ url()->current() }}">
                                        <input class="form_sea" type="text" placeholder="Cari produk.."
                                            name="keyword">
                                        <button type="submit" class="seach_icon"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            @if (auth()->guard('customer')->check())
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="icon-cus">
                                                <a href="{{ route('customer.cart.index') }}" class="text-white py-0"><i
                                                        class="fa fa-fw fa-shopping-cart"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="icon-cus">
                                                <a href="{{ route('customer.notification.index') }}"
                                                    class="text-white py-0"><i class="fa fa-regular fa-bell"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 profile-cus">
                                    <ul class="d-flex align-items-center">
                                        <li class="nav-item dropdown pe-3">
                                            <a class="nav-link nav-profile d-flex align-items-center pe-0 text-white"
                                                href="#" data-bs-toggle="dropdown">
                                                <img src="{{ auth()->guard('customer')->user()->img? url('/upload/admin/customer/',auth()->guard('customer')->user()->img): url('admin/assets/img/profile-img.jpg') }}"
                                                    alt="Profile" class="rounded-circle" style="max-width: 40px;">
                                                {{-- <img src="{{ url('admin/assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle" style="max-width: 40px;"> --}}
                                                <span
                                                    class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->guard('customer')->user()->username }}</span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center"
                                                        href="{{ route('customer.profile.index') }}">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                        <span>Profile</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center"
                                                        href="{{ route('auth.logout.customer') }}">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                        <span>Keluar</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="col-md-4 align-center">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="auth-button">
                                                <a href="{{ route('auth.register.register_customer') }}"
                                                    class="btn btn-outline-light btn-block">Daftar</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="auth-button">
                                                <a href="{{ route('auth.login.login_customer') }}"
                                                    class="btn btn-warning text-primary-emphasis btn-block">Masuk</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
