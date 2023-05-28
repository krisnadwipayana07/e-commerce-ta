<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-heading">Approved Payment Customer</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.evidence_payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.evidence_payment.index') }}">
                <i class="ri-wallet-line"></i><span>Approved Payment Customer</span>
            </a>
        </li>
        <li class="nav-heading">Submission Credit</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.dp.payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission.dp.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Down Payment</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.credit.payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission.credit.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Credit Payment</span>
            </a>
        </li>
        <li class="nav-heading">Submission Transfer</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.submission.transfer.payment.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.submission.transfer.payment.index') }}">
                <i class="ri-wallet-line"></i><span>Submission Transfer Payment</span>
            </a>
        </li>

        <li class="nav-heading">Users</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.admin.edit' ? '' : 'collapsed' }}"
                href="{{ route('admin.admin.edit',auth()->guard('admin')->user()->id) }}">
                <i class="ri-user-settings-line"></i><span>Profile</span>
            </a>
        </li>
    </ul>

</aside>
