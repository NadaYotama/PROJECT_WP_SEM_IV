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
    <title>@yield('title', 'Dashboard') - RM Padang Sederhana</title>
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
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .dashboard-card:hover {
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
    @stack('styles')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn’t work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- Sidebar toggle visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>
                    <!-- Logo -->
                    <a class="navbar-brand" href="#">
                        <b class="logo-icon p-l-10">
                            <img src="{{ asset('assets/images/logorumahmakan.jpg') }}" alt="homepage" class="light-logo" style="width: 20px; height: auto;" />
                        </b>
                        <span class="logo-text">
                            <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="light-logo" />
                        </span>
                    </a>
                    <!-- End Logo -->
                    <!-- Mobile toggle -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" 
                        data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ti-more"></i>
                    </a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" 
                               data-sidebartype="mini-sidebar">
                               <i class="mdi mdi-menu font-24"></i>
                            </a>
                        </li>
                        <!-- Additional nav items can be added here -->
                    </ul>
                    <div class="real-time-clock" id="real-time-clock"></div>
                    <ul class="navbar-nav float-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark notification-bell" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-bell font-24"></i>
                                @if(isset($notifications) && $notifications->count() > 0)
                                    <span class="notification-badge">{{ $notifications->count() }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Order updates</a>
                                <a class="dropdown-item" href="#">Reservation confirmations</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">View all</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="font-24 mdi mdi-comment-processing"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div>
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Reservation Confirmed</h5>
                                                        <span class="mail-desc">Your table reservation is confirmed</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-info btn-circle"><i class="ti-shopping-cart"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Order Ready</h5>
                                                        <span class="mail-desc">Your order is ready for pickup</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-primary btn-circle"><i class="ti-gift"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Special Offer</h5>
                                                        <span class="mail-desc">Check out our new menu items</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- User profile dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" data-toggle="dropdown" 
                               aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/images/users/1.jpg') }}" alt="user" class="rounded-circle" width="31">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti-settings m-r-5 m-l-5"></i> Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; padding: 0; margin: 0; width: 100%; text-align: left;">
                                        <i class="fa fa-power-off m-r-5 m-l-5"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- Left Sidebar -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30">
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ url()->current() == route('customer.dashboard') ? '#' : route('customer.dashboard') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>
                        <!-- More sidebar items can be added dynamically or conditionally -->
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- ============================================================== -->
        <!-- Page wrapper -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            @yield('content')
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
    </div>
    <!-- ============================================================== -->
    <!-- All Jquery and scripts -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    @stack('scripts')

    <script>
        // Prevent back button navigation by redirecting to dashboard
        window.history.replaceState(null, null, window.location.href);
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.location.href = '{{ url()->current() == route('customer.dashboard') ? route('customer.dashboard') : route('admin.dashboard') }}';
        };

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
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>

</html>
