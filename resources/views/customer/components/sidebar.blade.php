<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      {{-- <li class="nav-heading">Basic Canggu</li> --}}

      <li class="nav-heading">Transaction</li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'customer.transaction.create') ? '' : 'collapsed' }}" href="{{ route('customer.transaction.create') }}">
          <i class="ri-wallet-line"></i><span>New Transaction</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'customer.transaction.index' || Route::current()->getName() == 'customer.transaction.edit') ? '' : 'collapsed' }}" href="{{ route('customer.transaction.index') }}">
          <i class="ri-wallet-line"></i><span>List Transaction</span>
        </a>
      </li>
      

      <li class="nav-heading">Users</li>
      <li class="nav-item">
        <a class="nav-link {{ (Route::current()->getName() == 'customer.customer.index' || Route::current()->getName() == 'customer.customer.edit') ? '' : 'collapsed' }}" href="{{ route('customer.customer.index') }}">
          <i class="ri-user-settings-line"></i><span>Profile</span>
        </a>
      </li>
    </ul>

</aside>