<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- <li class="nav-heading">Basic Canggu</li> --}}

        <li class="nav-heading">Dashboard</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.dashboard.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.dashboard.index') }}">
                <i class="ri-dashboard-line"></i><span>Dashboard</span>
            </a>
        </li>

        <li class="nav-heading">Property</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.category_property.index' || Route::current()->getName() == 'admin.category_property.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.category_property.index') }}">
                <i class="ri-dashboard-line"></i><span>Category Property</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.sub_category_property.index' || Route::current()->getName() == 'admin.sub_category_property.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.sub_category_property.index') }}">
                <i class="ri-layout-4-line"></i><span>Sub Category Property</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.property.index' || Route::current()->getName() == 'admin.property.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.property.index') }}">
                <i class="ri-device-line"></i><span>Property</span>
            </a>
        </li>

        <li class="nav-heading">Transaction</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.category_payment.index' || Route::current()->getName() == 'admin.category_payment.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.category_payment.index') }}">
                <i class="ri-bank-card-line"></i><span>Category Payment</span>
            </a>
        </li>
        {{-- stock --}}
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.transaction.index' || Route::current()->getName() == 'admin.transaction.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.transaction.index') }}">
                <i class="ri-wallet-line"></i><span>Transaction</span>
            </a>
        </li>

        {{--
        <li class="nav-heading">Stock</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.stock.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.stock.index') }}">
                <i class="ri-wallet-line"></i><span>Stock</span>
            </a>
        </li>
        --}}
        <li class="nav-heading">Approved Payment Customer</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.evidence_payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.evidence_payment.index') }}">
                <i class="ri-wallet-line"></i><span>Approved Payment Customer</span>
            </a>
        </li>
        <li class="nav-heading">Submission Down Payment</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.dp.payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission.dp.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Down Payment</span>
            </a>
        </li>
        <li class="nav-heading">Submission Credit Payment</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.credit.payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission.credit.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Credit Payment</span>
            </a>
        </li>
        <li class="nav-heading">Submission Transfer Payment</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.transfer.payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission.transfer.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Transfer Payment</span>
            </a>
        </li>
        <li class="nav-heading">Sales Report</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.sales.report.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.sales.report.index') }}">
                <i class="ri-wallet-line"></i><span>Sales Report</span>
            </a>
        </li>
        <li class="nav-heading">Submission Premium Customer</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission_premium.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission_premium.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Premium Customer</span>
            </a>
        </li>

        <li class="nav-heading">Users</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.customer.index' || Route::current()->getName() == 'admin.customer.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.customer.index') }}">
                <i class="ri-user-heart-line"></i><span>Customer</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.admin.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.admin.index') }}">
                <i class="ri-user-settings-line"></i><span>Admin</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.admin.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.admin.edit',auth()->guard('admin')->user()->id) }}">
                <i class="ri-user-settings-line"></i><span>Profile</span>
            </a>
        </li>
    </ul>

</aside>
