<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- <li class="nav-heading">Basic Canggu</li> --}}

        <li class="nav-heading">Dashboard</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.dashboard.index' ? '' : 'collapsed' }}" href="{{ route('admin.dashboard.index') }}">
                <i class="ri-dashboard-line"></i><span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">Barang</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.category_property.index' || Route::current()->getName() == 'admin.category_property.edit' ? '' : 'collapsed' }}" href="{{ route('admin.category_property.index') }}">
                <i class="ri-dashboard-line"></i><span>Kategori Barang</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.sub_category_property.index' || Route::current()->getName() == 'admin.sub_category_property.edit' ? '' : 'collapsed' }}" href="{{ route('admin.sub_category_property.index') }}">
                <i class="ri-layout-4-line"></i><span>Sub Kategori Barang</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.property.index' || Route::current()->getName() == 'admin.property.edit' ? '' : 'collapsed' }}" href="{{ route('admin.property.index') }}">
                <i class="ri-device-line"></i><span>Barang</span>
            </a>
        </li>

        <li class="nav-heading">Transaksi</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.category_payment.index' || Route::current()->getName() == 'admin.category_payment.edit' ? '' : 'collapsed' }}" href="{{ route('admin.category_payment.index') }}">
                <i class="ri-bank-card-line"></i><span>Kategori Pembayaran</span>
            </a>
        </li>
        {{-- stock --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.transaction.index' || Route::current()->getName() == 'admin.transaction.edit' ? '' : 'collapsed' }}" href="{{ route('admin.transaction.index') }}">
                <i class="ri-wallet-line"></i><span>Transaksi</span>
            </a>
        </li>

        {{--
        <li class="nav-heading">Stok</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.stock.index' ? '' : 'collapsed' }}"
        href="{{ route('admin.stock.index') }}">
        <i class="ri-wallet-line"></i><span>Stok</span>
        </a>
        </li>
        --}}
        <li class="nav-heading">Pembayaran Pelanggan</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.evidence_payment.index' ? '' : 'collapsed' }}" href="{{ route('admin.evidence_payment.index') }}">
                <i class="ri-wallet-line"></i><span>Pembayaran Pelanggan</span>
            </a>
        </li>
        <li class="nav-heading">Pembayaran Kredit</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.dp.payment.index' ? '' : 'collapsed' }}" href="{{ route('admin.submission.dp.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Pembayaran Uang Muka (DP)</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.credit.payment.index' ? '' : 'collapsed' }}" href="{{ route('admin.submission.credit.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Pembayaran Angsuran Kredit</span>
            </a>
        </li>
        <li class="nav-heading">Pembayaran Transfer</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.transfer.payment.index' ? '' : 'collapsed' }}" href="{{ route('admin.submission.transfer.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Pembayaran Transfer Bank</span>
            </a>
        </li>
        <li class="nav-heading">Pengiriman</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.delivery.index' ? '' : 'collapsed' }}" href="{{ route('admin.delivery.index') }}">
                <i class="ri-truck-line"></i><span>Pengiriman</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.delivery.evidence.index' ? '' : 'collapsed' }}" href="{{ route('admin.delivery.evidence.index') }}">
                <i class="ri-truck-line"></i><span>Bukti Pengiriman</span>
            </a>
        </li>
        <li class="nav-heading">Laporan Penjualan</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.sales.report.index' ? '' : 'collapsed' }}" href="{{ route('admin.sales.report.index') }}">
                <i class="ri-wallet-line"></i><span>Laporan Penjualan</span>
            </a>
        </li>
        <li class="nav-heading">Pengajuan Pelanggan Premium</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission_premium.index' ? '' : 'collapsed' }}" href="{{ route('admin.submission_premium.index') }}">
                <i class="ri-wallet-line"></i><span>Pengajuan Pelanggan Premium</span>
            </a>
        </li>

        <li class="nav-heading">Users</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.customer.index' || Route::current()->getName() == 'admin.customer.edit' ? '' : 'collapsed' }}" href="{{ route('admin.customer.index') }}">
                <i class="ri-user-heart-line"></i><span>Pelanggan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.admin.index' ? '' : 'collapsed' }}" href="{{ route('admin.admin.index') }}">
                <i class="ri-user-settings-line"></i><span>Admin</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.admin.edit' ? '' : 'collapsed' }}" href="{{ route('admin.admin.edit',auth()->guard('admin')->user()->id) }}">
                <i class="ri-user-settings-line"></i><span>Profil</span>
            </a>
        </li>
    </ul>

</aside>