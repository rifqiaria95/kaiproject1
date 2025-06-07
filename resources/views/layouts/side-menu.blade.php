<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="{{ url('assets/img/branding/logo.png') }}" alt="Logo" width="40">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Kainnova</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboards -->
            <li class="menu-item  {{ Request::is('dashboard')?'active':'' }}">
                <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>
            @can('view menu group')
                @if(isset($menuGroups) && $menuGroups->isNotEmpty())
                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Transaksi">Transaksi</span>
                    </li>

                    @foreach ($menuGroups as $menuGroup)
                        @if($menuGroup->jenis_menu == 2)
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link {{ $menuGroup->menuDetails->isNotEmpty() ? 'menu-toggle' : '' }}">
                                    <i class="menu-icon tf-icons {{ $menuGroup->icon }}"></i>
                                    <div data-i18n="{{ $menuGroup->name }}">{{ $menuGroup->name }}</div>
                                    @if ($menuGroup->menuDetails->isNotEmpty())
                                        <span class="menu-arrow"></span>
                                    @endif
                                </a>

                                @if ($menuGroup->menuDetails->isNotEmpty())
                                    <ul class="menu-sub">
                                        @foreach ($menuGroup->menuDetails as $menuDetail)
                                            <li class="menu-item">
                                                <a href="{{ $menuDetail->route }}" class="menu-link">
                                                    <div data-i18n="{{ $menuDetail->name }}">{{ $menuDetail->name }}</div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="menu-header small">
                        <span class="menu-header-text">No menu available</span>
                    </li>
                @endif

                @if(isset($menuGroups) && $menuGroups->isNotEmpty())
                    <li class="menu-header small">
                        <span class="menu-header-text" data-i18n="Master">Master</span>
                    </li>

                    @foreach ($menuGroups as $menuGroup)
                        @if($menuGroup->jenis_menu == 1)
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link {{ $menuGroup->menuDetails->isNotEmpty() ? 'menu-toggle' : '' }}">
                                    <i class="menu-icon tf-icons {{ $menuGroup->icon }}"></i>
                                    <div data-i18n="{{ $menuGroup->name }}">{{ $menuGroup->name }}</div>
                                    @if ($menuGroup->menuDetails->isNotEmpty())
                                        <span class="menu-arrow"></span>
                                    @endif
                                </a>

                                @if ($menuGroup->menuDetails->isNotEmpty())
                                    <ul class="menu-sub">
                                        @foreach ($menuGroup->menuDetails as $menuDetail)
                                            <li class="menu-item">
                                                <a href="{{ $menuDetail->route }}" class="menu-link">
                                                    <div data-i18n="{{ $menuDetail->name }}">{{ $menuDetail->name }}</div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="menu-header small">
                        <span class="menu-header-text">No menu available</span>
                    </li>
                @endif

                @if(isset($menuGroups) && $menuGroups->isNotEmpty())
                    @role('superadmin')
                        <li class="menu-header small">
                            <span class="menu-header-text" data-i18n="Settings">Settings</span>
                        </li>
                        @foreach ($menuGroups as $menuGroup)
                            @if($menuGroup->jenis_menu == 3)
                                <li class="menu-item">
                                    <a href="javascript:void(0);" class="menu-link {{ $menuGroup->menuDetails->isNotEmpty() ? 'menu-toggle' : '' }}">
                                        <i class="menu-icon tf-icons {{ $menuGroup->icon }}"></i>
                                        <div data-i18n="{{ $menuGroup->name }}">{{ $menuGroup->name }}</div>
                                        @if ($menuGroup->menuDetails->isNotEmpty())
                                            <span class="menu-arrow"></span>
                                        @endif
                                    </a>

                                    @if ($menuGroup->menuDetails->isNotEmpty())
                                        <ul class="menu-sub">
                                            @foreach ($menuGroup->menuDetails as $menuDetail)
                                                <li class="menu-item">
                                                    <a href="{{ $menuDetail->route }}" class="menu-link">
                                                        <div data-i18n="{{ $menuDetail->name }}">{{ $menuDetail->name }}</div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    @endrole
                @else
                    <li class="menu-header small">
                        <span class="menu-header-text">No menu available</span>
                    </li>
                @endif
            @endcan
        </ul>
      </aside>
      <!-- / Menu -->

      <div class="layout-page">
