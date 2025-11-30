<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('adminlte') }}/img/AdminLTELogo.png" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte') }}/img/user2-160x160.jpg"
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Users -->
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Users</p>
                </a>
            </li>

            <!-- Products Section (Categories, Units, Items) -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.categories.*','admin.units.*','admin.items.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.categories.*','admin.units.*','admin.items.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                        Products
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.units.index') }}" class="nav-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Units</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.items.index') }}" class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Items</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Clients & Sales Section -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.clients.*','admin.sales.*','admin.returns.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.clients.*','admin.sales.*','admin.returns.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-bag"></i>
                    <p>
                        Transactions
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Clients</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.sales.index') }}" class="nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sales</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.returns.create') }}" class="nav-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Returns</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Warehouses & Safe -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.warehouses.*','admin.safes.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.warehouses.*','admin.safes.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-warehouse"></i>
                    <p>
                        Storage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.warehouses.index') }}" class="nav-link {{ request()->routeIs('admin.warehouses.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Warehouses</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.safes.index') }}" class="nav-link {{ request()->routeIs('admin.safes.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Safe</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Settings -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.settings.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p>
                        Settings
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.general.view') }}" class="nav-link {{ request()->routeIs('admin.settings.general.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>General</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.advanced.view') }}" class="nav-link {{ request()->routeIs('admin.settings.advanced.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Advanced</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Roles & Permissions -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.roles.*','admin.permissions.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('admin.roles.*','admin.permissions.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-shield"></i>
                    <p>
                        Access Control
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
</nav>


        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
