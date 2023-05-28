<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-heading">Delivery</li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.delivery.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.delivery.index') }}">
                <i class="ri-truck-line"></i><span>Delivery</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::current()->getName() == 'admin.delivery.evidence.index' ? '' : 'collapsed' }}"
                href="{{ route('admin.delivery.evidence.index') }}">
                <i class="ri-truck-line"></i><span>Submission Delivery Arrived</span>
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
