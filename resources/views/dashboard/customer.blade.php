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
    <title>Customer Dashboard - RM Padang Sederhana</title>
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
        .customer-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .customer-card:hover {
            transform: translateY(-5px);
        }
        .order-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .reservation-card {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        .favorite-card {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
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
        .menu-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: box-shadow 0.2s;
        }
        .menu-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .menu-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
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
                    <a class="navbar-brand" href="{{ route('customer.dashboard') }}">
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
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <span class="d-none d-md-block">Quick Order <i class="fa fa-angle-down"></i></span>
                             <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('reservation.index') }}"><i class="mdi mdi-table-chair mr-2"></i>Reserve Table</a>
                                <a class="dropdown-item" href="{{ route('customer.reservations.index') }}"><i class="mdi mdi-calendar-check mr-2"></i>View Reservations</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-star mr-2"></i>Favorites</a>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search menu &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
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
                                <a class="dropdown-item" href="#">Order updates</a>
                                <a class="dropdown-item" href="#">Reservation confirmations</a>
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
                                                        <h5 class="m-b-0">Reservation Confirmed</h5>
                                                        <span class="mail-desc">Your table reservation is confirmed</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-info btn-circle"><i class="ti-shopping-cart"></i></span>
                                                    <div class="m-l-10">
                                                        <h5 class="m-b-0">Order Ready</h5>
                                                        <span class="mail-desc">Your order is ready for pickup</span>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- Message -->
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
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('customer.dashboard') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>
                        <!-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('customer.menu.index') }}" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Menu</span></a></li> -->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('customer.menu.index') }}" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Order Food</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-receipt"></i><span class="hide-menu">My Orders</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('customer.orders.index') }}" class="sidebar-link"><i class="mdi mdi-clipboard-list"></i><span class="hide-menu"> Order History </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('customer.orders.current') }}" class="sidebar-link"><i class="mdi mdi-clock-outline"></i><span class="hide-menu"> Current Orders </span></a></li>
                                <!-- <li class="sidebar-item"><a href="{{ route('reviews.index') }}" class="sidebar-link"><i class="mdi mdi-star"></i><span class="hide-menu"> Reviews </span></a></li> -->
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-table-chair"></i><span class="hide-menu">Reservations</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('customer.reservations.create') }}" class="sidebar-link"><i class="mdi mdi-calendar-plus"></i><span class="hide-menu"> New Reservation </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('customer.reservations.index') }}" class="sidebar-link"><i class="mdi mdi-calendar-check"></i><span class="hide-menu"> My Reservations </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('customer.reservations.index') }}?status=completed" class="sidebar-link"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu"> Reservation History </span></a></li>
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-wallet"></i><span class="hide-menu">Payments</span></a>
                          <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('customer.payments.methods') }}" class="sidebar-link"><i class="mdi mdi-credit-card"></i><span class="hide-menu"> Payment Methods </span></a></li>
                                <li class="sidebar-item"><a href="{{ route('customer.payments.history') }}" class="sidebar-link"><i class="mdi mdi-receipt"></i><span class="hide-menu"> Transaction History </span></a></li>
                                <!-- <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-wallet-giftcard"></i><span class="hide-menu"> Loyalty Points </span></a></li> -->
                            </ul>
                        </li>

                        <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-cog"></i><span class="hide-menu">Settings</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item"><a href="{{ route('profile.edit') }}" class="sidebar-link"><i class="mdi mdi-account"></i><span class="hide-menu"> My Profile </span></a></li>
                                <!-- <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-bell-ring"></i><span class="hide-menu"> Notifications </span></a></li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i class="mdi mdi-lock"></i><span class="hide-menu"> Privacy </span></a></li> -->
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
                        <h4 class="page-title">Dashboard Customer</h4>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Welcome Back</li>
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
                <!-- Welcome Message and Quick Stats -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card customer-card">
                            <div class="card-body">
                                <h5 class="card-title">Welcome back, {{ auth()->user()->name ?? 'Customer' }}!</h5>
                                <p class="card-text">Ready to enjoy some delicious Padang cuisine? Check out our latest offers and place your order.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('customer.menu.index') }}" class="btn btn-primary">
                                        <i class="mdi mdi-cart mr-1"></i>Order Now
                                    </a>
                                    <a href="{{ route('customer.reservations.create') }}" class="btn btn-success">
                                        <i class="mdi mdi-calendar-plus mr-1"></i>Make Reservation
                                    </a>
                                    <a href="{{ route('customer.reservations.index') }}" class="btn btn-outline-info">
                                        <i class="mdi mdi-calendar-check mr-1"></i>My Reservations
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="card customer-card order-card">
                                    <div class="card-body text-center">
                                        <h4>{{ $stats['total_orders'] ?? 0 }}</h4>
                                        <p>Total Orders</p>
                                        <i class="fas fa-shopping-cart fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card customer-card reservation-card">
                                    <div class="card-body text-center">
                                        <h4>{{ $stats['active_reservations'] ?? 0 }}</h4>
                                        <p>Active Reservations</p>
                                        <i class="fas fa-calendar-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card customer-card favorite-card">
                                    <div class="card-body text-center">
                                        <h4>{{ $stats['favorite_items'] ?? 0 }}</h4>
                                        <p>Favorite Items</p>
                                        <i class="fas fa-heart fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Order Food Section -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Order Food</h5>
                                <div>
                                    <a href="{{ route('customer.cart.index') }}" class="btn btn-sm btn-primary mr-2">View Cart</a>
                                    <a href="{{ route('customer.cart.checkout') }}" class="btn btn-sm btn-success">Checkout</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if($menus && count($menus) > 0)
                                    <div class="row">
                                        @foreach($menus as $item)
                                        @if($item['is_available'])
                                        <div class="col-md-4 mb-3">
                                            <div class="menu-item d-flex">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="menu-image mr-3">
                                                <div class="flex-grow-1">
                                                    <h5>{{ $item['name'] }}</h5>
                                                    <p class="text-muted">{{ $item['description'] }}</p>
                                                    <p class="font-weight-bold text-primary">Rp {{ number_format($item['price']) }}</p>
                                                    <form action="{{ route('customer.cart.add', $item['id']) }}" method="POST" class="form-inline">
                                                        @csrf
                                                        <div class="form-group mr-2">
                                                            <label for="quantity-{{ $item['id'] }}" class="sr-only">Quantity</label>
                                                            <input type="number" name="quantity" id="quantity-{{ $item['id'] }}" value="1" min="1" max="10" class="form-control form-control-sm" required>
                                                        </div>
                                                        <div class="form-group mr-2">
                                                            <label for="notes-{{ $item['id'] }}" class="sr-only">Notes</label>
                                                            <input type="text" name="notes" id="notes-{{ $item['id'] }}" placeholder="Notes (optional)" maxlength="500" class="form-control form-control-sm">
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">Order</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p>No menu items available at this time.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Featured Menu Items -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Featured Menu Items</h5>
                                <a href="#" class="btn btn-sm btn-primary">View All Menu</a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if(isset($featuredMenus) && $featuredMenus->count() > 0)
                                        @foreach($featuredMenus as $item)
                                            @php
                                                $isUrl = filter_var($item->image, FILTER_VALIDATE_URL);
                                                $imageUrl = $isUrl ? $item->image : asset('storage/' . $item->image);
                                            @endphp
                                            <div class="col-md-4 mb-3">
                                                <div class="menu-item">
                                                    <div class="d-flex">
                                                <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" class="menu-image mr-3">
                                                        <div class="flex-grow-1">
                                                            <h6>{{ $item->name }}</h6>
                                                            <p class="text-muted mb-1">{{ Str::limit($item->description ?? '', 50) }}</p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="font-weight-bold text-primary">Rp {{ number_format($item->price ?? 0) }}</span>
                                                                <button class="btn btn-sm btn-outline-primary">Order</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center">
                                            <p>No featured menu items available</p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Recent Orders and Upcoming Reservations -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Recent Orders</h5>
                                <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-primary">View All Orders</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($orderHistory) && $orderHistory->count() > 0)
                                                @foreach($orderHistory as $order)
                                                <tr>
                                                    <td>{{ $order->order_number ?? '#' . $order->id }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                    <td><span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></td>
                                                    <td>Rp {{ number_format($order->total ?? 0) }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent orders</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Upcoming Reservations</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Guests</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($upcomingReservations))
                                                @foreach($upcomingReservations as $reservation)
                                                <tr>
                                                    <td>{{ $reservation->date ? $reservation->date->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>{{ $reservation->time ? $reservation->time->format('H:i') : 'N/A' }}</td>
                                                    <td>{{ $reservation->guests }}</td>
                                                    <td><span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : 'warning' }}">{{ ucfirst($reservation->status) }}</span></td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center">No upcoming reservations</td>
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
                <!-- Quick Actions -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Quick Actions</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <a href="{{ route('customer.menu.index') }}" class="btn btn-primary btn-block mb-2">Order Food</a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('customer.reservations.index') }}" class="btn btn-success btn-block mb-2">My Reservations</a>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-info btn-block mb-2">View Favorites</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-warning btn-block mb-2">Contact Support</button>
                                    </div>
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
            window.location.href = '{{ route('customer.dashboard') }}';
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

</body>

</html>
