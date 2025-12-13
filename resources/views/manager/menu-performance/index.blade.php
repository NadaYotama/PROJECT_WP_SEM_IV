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
    <title>Menu Performance - RM Padang Sederhana</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/icons/font-awesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/icons/material-design-iconic-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet">
    <style>
        .performance-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .performance-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .top-menu-card {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        .category-card {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
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
                    <a class="navbar-brand" href="{{ route('manager.dashboard') }}">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('assets/images/logorumahmakan.jpg') }}" alt="homepage" class="light-logo" style="width: 20px; height: auto;" />

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
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/images/users/1.jpg') }}" alt="user" class="rounded-circle" width="31">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('manager.settings') }}"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; padding: 0; margin: 0; width: 100%; text-align: left;"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</button>
                                </form>
                                <div class="dropdown-divider"></div>
                                <div class="p-l-30 p-10"><a href="{{ route('profile.edit') }}" class="btn btn-sm btn-success btn-rounded">View Profile</a></div>
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
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('manager.dashboard') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Menu Management</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('manager.menu.index') }}" class="sidebar-link"><i class="mdi mdi-food-variant"></i><span class="hide-menu"> All Menus </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('manager.categories.index') }}" class="sidebar-link"><i class="mdi mdi-food-off"></i><span class="hide-menu"> Categories </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-account-group"></i><span class="hide-menu">Staff Management</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('manager.staff.index') }}" class="sidebar-link"><i class="mdi mdi-account-multiple"></i><span class="hide-menu"> All Staff </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('manager.staff.create') }}" class="sidebar-link"><i class="mdi mdi-account-plus"></i><span class="hide-menu"> Add Staff </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('manager.performance.index') }}" class="sidebar-link"><i class="mdi mdi-chart-line"></i><span class="hide-menu"> Performance </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span class="hide-menu">Reports & Analytics</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('manager.reports.index') }}" class="sidebar-link"><i class="mdi mdi-chart-line"></i><span class="hide-menu"> Sales Report </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('manager.menu-performance.index') }}" class="sidebar-link"><i class="mdi mdi-chart-pie"></i><span class="hide-menu"> Menu Performance </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu"> Reservation Report </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-trending-up"></i><span class="hide-menu"> Revenue Analytics </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-cog"></i><span class="hide-menu">Settings</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('profile.edit') }}" class="sidebar-link"><i class="mdi mdi-account"></i><span class="hide-menu"> Manager Profile </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-bell-ring"></i><span class="hide-menu"> Notifications </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-store"></i><span class="hide-menu"> Restaurant Settings </span></a></li>
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
                        <h4 class="page-title">Menu Performance Analytics</h4>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Menu Performance</li>
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
                <!-- Date Range Filter -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('manager.menu-performance.index') }}" class="form-inline">
                                    <div class="form-group mr-3">
                                        <label for="start_date" class="mr-2">Start Date:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
                                    </div>
                                    <div class="form-group mr-3">
                                        <label for="end_date" class="mr-2">End Date:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Performance Summary Cards -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card performance-card stats-card">
                            <div class="card-body text-center">
                                <h4>Rp {{ number_format($totalRevenue) }}</h4>
                                <p>Total Revenue</p>
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card performance-card top-menu-card">
                            <div class="card-body text-center">
                                <h4>{{ $totalQuantity }}</h4>
                                <p>Total Items Sold</p>
                                <i class="fas fa-utensils fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card performance-card category-card">
                            <div class="card-body text-center">
                                <h4>{{ $totalOrders }}</h4>
                                <p>Total Orders</p>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Top Performing Menus -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Top 10 Performing Menus</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Menu Name</th>
                                                <th>Category</th>
                                                <th>Quantity Sold</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topMenus as $menu)
                                            <tr>
                                                <td>{{ $menu->name }}</td>
                                                <td>{{ $menu->category }}</td>
                                                <td>{{ $menu->total_quantity }}</td>
                                                <td>Rp {{ number_format($menu->total_revenue) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Category Performance</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Items Sold</th>
                                                <th>Revenue</th>
                                                <th>Orders</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($categoryPerformance as $category)
                                            <tr>
                                                <td>{{ $category->category }}</td>
                                                <td>{{ $category->total_quantity }}</td>
                                                <td>Rp {{ number_format($category->total_revenue) }}</td>
                                                <td>{{ $category->total_orders }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Detailed Menu Performance Table -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detailed Menu Performance</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="menuPerformanceTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Menu Name</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Quantity Sold</th>
                                                <th>Total Revenue</th>
                                                <th>Orders Count</th>
                                                <th>Avg. Order Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($menuPerformance as $menu)
                                            <tr>
                                                <td>{{ $menu->name }}</td>
                                                <td>{{ $menu->category }}</td>
                                                <td>Rp {{ number_format($menu->price) }}</td>
                                                <td>{{ $menu->total_quantity }}</td>
                                                <td>Rp {{ number_format($menu->total_revenue) }}</td>
                                                <td>{{ $menu->total_orders }}</td>
                                                <td>Rp {{ $menu->total_orders > 0 ? number_format($menu->total_revenue / $menu->total_orders) : 0 }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No menu performance data available for the selected period</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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
            window.location.href = '{{ route('manager.dashboard') }}';
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
        $('#menuPerformanceTable').DataTable();
    </script>
</body>

</html>
