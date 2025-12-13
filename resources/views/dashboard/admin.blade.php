<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Prevent caching and back button -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Favicon icon -->
    <link rel="icon" type="image/jpeg" sizes="16x16" href="{{ asset('assets/images/logorumahmakan.jpg') }}">
    <title>Admin Dashboard - RM Padang Sederhana</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/icons/font-awesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/icons/material-design-iconic-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet">
    <style>
        .real-time-clock {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            margin-left: 20px;
        }
        .admin-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .admin-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .stats-card.users {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }
        .stats-card.revenue {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }
        .stats-card.orders {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
        }
        .notification-bell {
            position: relative;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f44336;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('assets/images/logorumahmakan.jpg') }}" alt="homepage" class="light-logo" style="width: 40px; height: auto;" />

                        </b>
                        <!--End Logo icon -->
                         <!-- Logo text -->
                        <span class="logo-text">
                             <!-- dark Logo text -->
                             <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="light-logo" />

                        </span>
                        <!-- Logo icon -->
                        <!-- <b class="logo-icon"> -->
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <!-- <img src="assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

                        <!-- </b> -->
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <span class="d-none d-md-block">Quick Add <i class="fa fa-angle-down"></i></span>
                             <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <button class="dropdown-item" data-toggle="modal" data-target="#addUserModal"><i class="mdi mdi-account-plus mr-2"></i>Tambah User</button>
                                <button class="dropdown-item" data-toggle="modal" data-target="#addMenuModal"><i class="mdi mdi-food mr-2"></i>Tambah Menu</button>
                                <button class="dropdown-item" data-toggle="modal" data-target="#addTableModal"><i class="mdi mdi-table-chair mr-2"></i>Tambah Meja</button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item" data-toggle="modal" data-target="#systemSettingsModal"><i class="mdi mdi-cog mr-2"></i>System Settings</button>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li>
                    </ul>
                    <div class="real-time-clock" id="real-time-clock"></div>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark notification-bell" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell font-24"></i>
                @if(isset($notifications) && $notifications->count() > 0)
                <span class="notification-badge">{{ $notifications->count() }}</span>
                @endif
                            </a>
                             <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">System notifications</a>
                                <a class="dropdown-item" href="#">User activities</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">View all</a>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-24 mdi mdi-comment-processing"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="">
                                             <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">System Update</h5>
                                                        <span class="mail-desc">New features available</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-info btn-circle"><i class="ti-settings"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Backup Complete</h5>
                                                        <span class="mail-desc">Daily backup finished</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-primary btn-circle"><i class="ti-user"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">New User</h5>
                                                        <span class="mail-desc">New customer registered</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/images/users/1.jpg') }}" alt="user" class="rounded-circle" width="31">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i> My Balance</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i> Inbox</a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; padding: 0; margin: 0; width: 100%; text-align: left;"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</button>
                                </form>
                                <div class="dropdown-divider"></div>
                                <div class="p-l-30 p-10"><a href="javascript:void(0)" class="btn btn-sm btn-success btn-rounded">View Profile</a></div>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30">
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-account-group"></i><span class="hide-menu">User Management</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('admin.users.index') }}" class="sidebar-link"><i class="mdi mdi-account-multiple"></i><span class="hide-menu"> All Users </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.users.create') }}" class="sidebar-link"><i class="mdi mdi-account-plus"></i><span class="hide-menu"> Add User </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.users.index') }}" class="sidebar-link"><i class="mdi mdi-account-edit"></i><span class="hide-menu"> User Roles </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Menu Management</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('admin.menu.index') }}" class="sidebar-link"><i class="mdi mdi-food-variant"></i><span class="hide-menu"> All Menus </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.categories.index') }}" class="sidebar-link"><i class="mdi mdi-food-off"></i><span class="hide-menu"> Categories </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.menu.create') }}" class="sidebar-link"><i class="mdi mdi-plus-circle"></i><span class="hide-menu"> Add Menu </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-table-chair"></i><span class="hide-menu">Table Management</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('admin.tables.index') }}" class="sidebar-link"><i class="mdi mdi-table-multiple"></i><span class="hide-menu"> All Tables </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.tables.create') }}" class="sidebar-link"><i class="mdi mdi-table-plus"></i><span class="hide-menu"> Add Table </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.reservations.index') }}" class="sidebar-link"><i class="mdi mdi-calendar-check"></i><span class="hide-menu"> Reservations </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span class="hide-menu">Reports</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('admin.reports.index') }}" class="sidebar-link"><i class="mdi mdi-chart-line"></i><span class="hide-menu"> Sales Report </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.reports.index') }}" class="sidebar-link"><i class="mdi mdi-chart-pie"></i><span class="hide-menu"> Revenue Report </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.user-activity.index') }}" class="sidebar-link"><i class="mdi mdi-file-chart"></i><span class="hide-menu"> User Activity </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-cog"></i><span class="hide-menu">System Settings</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('profile.edit') }}" class="sidebar-link"><i class="mdi mdi-account"></i><span class="hide-menu"> Admin Profile </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('admin.balance.index') }}" class="sidebar-link"><i class="mdi mdi-wallet"></i><span class="hide-menu"> Balance Management </span></a></li>
                                <!-- <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-bell-ring"></i><span class="hide-menu"> Notifications </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-database"></i><span class="hide-menu"> Database </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-shield-lock"></i><span class="hide-menu"> Security </span></a></li> -->
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Dashboard Admin</h4>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Ringkasan Sistem</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Quick Stats Cards -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card admin-card stats-card users">
                            <div class="card-body text-center">
                                <h4>{{ $stats['orders_today'] ?? 0 }}</h4>
                                <p>Orders Today</p>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card stats-card orders">
                            <div class="card-body text-center">
                                <h4>{{ $stats['pending_orders'] ?? 0 }}</h4>
                                <p>Pending Orders</p>
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card stats-card revenue">
                            <div class="card-body text-center">
                                <h4>Rp {{ number_format($reports['total_revenue'] ?? 0) }}</h4>
                                <p>Monthly Revenue</p>
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card stats-card">
                            <div class="card-body text-center">
                                <h4>{{ $stats['ready_orders'] ?? 0 }}</h4>
                                <p>Ready Orders</p>
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Stock Stats Cards -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card admin-card stats-card">
                            <div class="card-body text-center">
                                <h4>{{ $stats['low_stock_items'] ?? 0 }}</h4>
                                <p>Low Stock Items (≤5)</p>
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card admin-card stats-card">
                            <div class="card-body text-center">
                                <h4>{{ $stats['out_of_stock_items'] ?? 0 }}</h4>
                                <p>Out of Stock Items</p>
                                <i class="fas fa-times-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card admin-card stats-card">
                            <div class="card-body text-center">
                                <h4>Rp {{ number_format($stats['total_stock_value'] ?? 0) }}</h4>
                                <p>Total Stock Value</p>
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Management Cards -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card admin-card">
                            <div class="card-body text-center">
                                <h4>{{ $management['pending_reservations'] ?? 0 }}</h4>
                                <p>Pending Reservations</p>
                                <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card">
                            <div class="card-body text-center">
                                <h4>{{ $management['reservations_today'] ?? 0 }}</h4>
                                <p>Today's Reservations</p>
                                <i class="fas fa-calendar-check fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card">
                            <div class="card-body text-center">
                                <h4>{{ $management['total_menus'] ?? 0 }}</h4>
                                <p>Total Menus</p>
                                <i class="fas fa-utensils fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card admin-card">
                            <div class="card-body text-center">
                                <h4>{{ $management['available_tables'] ?? 0 }}</h4>
                                <p>Available Tables</p>
                                <i class="fas fa-table fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Recent Activity and System Health -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Recent System Activity</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Activity</th>
                                                <th>User</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($recentActivities))
                                                @foreach($recentActivities as $activity)
                                                <tr>
                                                    <td>{{ $activity->created_at->format('H:i') }}</td>
                                                    <td>{{ $activity->description }}</td>
                                                    <td>{{ $activity->user->name ?? 'System' }}</td>
                                                    <td><span class="badge badge-success">{{ $activity->status }}</span></td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent activities</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>System Health</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Server Status</span>
                                        <span class="badge badge-success">Online</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Database</span>
                                        <span class="badge badge-success">Connected</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Storage</span>
                                        <span class="badge badge-warning">85% Used</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Backup</span>
                                        <span class="badge badge-success">Last: Today</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Recent Stock Reductions -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Recent Stock Reductions</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Menu Item</th>
                                            <th>Quantity Reduced</th>
                                            <th>Current Stock</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($recentStockReductions) && $recentStockReductions->count() > 0)
                                                @foreach($recentStockReductions as $reduction)
                                                <tr>
                                                    <td>{{ $reduction->order_number }}</td>
                                                    <td>{{ $reduction->item_name }}</td>
                                                    <td><span class="badge badge-danger">-{{ $reduction->quantity }}</span></td>
                                                    <td>{{ $reduction->current_stock ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($reduction->created_at)->format('Y-m-d H:i') }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No recent stock reductions</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Recent Reservations -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Recent Reservations</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>Table</th>
                                                <th>Date & Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($recentReservations) && $recentReservations->count() > 0)
                                                @foreach($recentReservations as $reservation)
                                                <tr>
                                                    <td>{{ $reservation->id }}</td>
                                                    <td>{{ $reservation->name ?? $reservation->user->name ?? 'N/A' }}</td>
                                                    <td>{{ $reservation->table->table_number ?? 'N/A' }}</td>
                                                    <td>{{ $reservation->date->format('Y-m-d') }} {{ $reservation->time->format('H:i') }}</td>
                                                    <td><span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($reservation->status) }}</span></td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No recent reservations</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- User Management Overview -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>User Management Overview</h5>
                                <button class="btn btn-primary btn-sm">Manage Users</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4>{{ $userStats['admins'] ?? 0 }}</h4>
                                            <p>Administrators</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4>{{ $userStats['managers'] ?? 0 }}</h4>
                                            <p>Managers</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4>{{ $userStats['kasirs'] ?? 0 }}</h4>
                                            <p>Kasirs</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h4>{{ $userStats['customers'] ?? 0 }}</h4>
                                            <p>Customers</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Quick Actions -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Menu Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($menuItems as $menu)
                                    <div class="col-md-2 text-center mb-3">
                                        @if($menu->image)
                                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" width="50" height="50" class="rounded">
                                        @else
                                        <span class="text-muted">No Image</span>
                                        @endif
                                        <p class="mt-2">{{ $menu->name }}</p>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- <div class="d-flex justify-content-center">
                                    {{ $menuItems->links() }}
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                All Rights Reserved by Nada Yotama. Designed and Developed by <a href="https://wrappixel.com">Universitas Bina Sarana Informatika</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!-- this page js -->
    <script src="{{ asset('assets/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>

    <!-- Prevent back button -->
    <script>
        // Prevent back button navigation by redirecting to dashboard
        window.history.replaceState(null, null, window.location.href);
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.location.href = '{{ route('admin.dashboard') }}';
        };

        // Additional prevention for backspace key
        document.addEventListener('keydown', function(e) {
            if (e.keyCode === 8 && !e.target.matches('input, textarea')) {
                e.preventDefault();
            }
        });
    </script>
    <script>
        /****************************************
         *       Basic Table                   *
         ****************************************/
        $('.table').DataTable();
    </script>
    <script>
        // Real-time clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('real-time-clock').textContent = timeString;
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    <!-- Modals for Quick Add -->
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Menu Modal -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuModalLabel">Tambah Menu Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="menu_name">Nama Menu</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="menu_name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="main_course" {{ old('category') == 'main_course' ? 'selected' : '' }}>Hidangan Utama</option>
                                        <option value="appetizer" {{ old('category') == 'appetizer' ? 'selected' : '' }}>Hidangan Pembuka</option>
                                        <option value="beverage" {{ old('category') == 'beverage' ? 'selected' : '' }}>Minuman</option>
                                        <option value="dessert" {{ old('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                        <option value="side_dish" {{ old('category') == 'side_dish' ? 'selected' : '' }}>Lauk Pendamping</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Harga (Rp)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="preparation_time">Waktu Persiapan (menit)</label>
                                    <input type="number" class="form-control @error('preparation_time') is-invalid @enderror" id="preparation_time" name="preparation_time" value="{{ old('preparation_time', 15) }}" min="1">
                                    @error('preparation_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Gambar Menu</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">Tersedia untuk dipesan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_popular">Menu Populer</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Table Modal -->
    <div class="modal fade" id="addTableModal" tabindex="-1" role="dialog" aria-labelledby="addTableModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTableModalLabel">Tambah Meja Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.tables.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="table_number">Nomor Meja</label>
                            <input type="text" class="form-control @error('table_number') is-invalid @enderror" id="table_number" name="table_number" value="{{ old('table_number') }}" required>
                            @error('table_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="capacity">Kapasitas</label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', 4) }}" min="1" required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="location">Lokasi</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Tersedia</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Simpan Meja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- System Settings Modal -->
    <div class="modal fade" id="systemSettingsModal" tabindex="-1" role="dialog" aria-labelledby="systemSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="systemSettingsModalLabel">System Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="restaurant_name">Restaurant Name</label>
                                    <input type="text" class="form-control @error('restaurant_name') is-invalid @enderror" id="restaurant_name" name="restaurant_name" value="{{ old('restaurant_name', 'RM Padang Sederhana') }}" required>
                                    @error('restaurant_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="restaurant_phone">Phone</label>
                                    <input type="tel" class="form-control @error('restaurant_phone') is-invalid @enderror" id="restaurant_phone" name="restaurant_phone" value="{{ old('restaurant_phone') }}">
                                    @error('restaurant_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="restaurant_address">Address</label>
                            <textarea class="form-control @error('restaurant_address') is-invalid @enderror" id="restaurant_address" name="restaurant_address" rows="3">{{ old('restaurant_address') }}</textarea>
                            @error('restaurant_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="opening_hours">Opening Hours</label>
                                    <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', '08:00 - 22:00') }}">
                                    @error('opening_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timezone">Timezone</label>
                                    <select class="form-control @error('timezone') is-invalid @enderror" id="timezone" name="timezone">
                                        <option value="Asia/Jakarta" {{ old('timezone') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                        <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="enable_notifications" name="enable_notifications" value="1" {{ old('enable_notifications', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_notifications">Enable System Notifications</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode') ? 'checked' : '' }}>
                            <label class="form-check-label" for="maintenance_mode">Maintenance Mode</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
