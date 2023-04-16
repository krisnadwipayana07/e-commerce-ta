<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      {{-- <li class="nav-heading">Basic Canggu</li> --}}
      
      <li class="nav-heading">Dashboard</li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'admin.dashboard.index') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard.index') }}">
          <i class="ri-dashboard-line"></i><span>Dashboard</span>
        </a>
      </li>

      <li class="nav-heading">Property</li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'admin.property.index' || Route::current()->getName() == 'admin.property.edit') ? '' : 'collapsed' }}" href="{{ route('admin.property.index') }}">
          <i class="ri-device-line"></i><span>Property</span>
        </a>
      </li>

      <li class="nav-heading">Transaction</li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'admin.transaction.index' || Route::current()->getName() == 'admin.transaction.edit') ? '' : 'collapsed' }}" href="{{ route('admin.transaction.index') }}">
          <i class="ri-wallet-line"></i><span>Transaction</span>
        </a>
      </li>
      
      <li class="nav-heading">Users</li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'admin.admin.edit') ? '' : 'collapsed' }}" href="{{ route('admin.admin.edit', auth()->guard('admin')->user()->id) }}">
          <i class="ri-user-settings-line"></i><span>Profile</span>
        </a>
      </li>
    </ul>

</aside>