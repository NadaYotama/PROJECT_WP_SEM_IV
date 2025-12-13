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
    <title>Order Food - RM Padang Sederhana</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/extra-libs/multicheck/multicheck.css') }}">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/icons/font-awesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/icons/material-design-iconic-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet">
    <style>
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
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .filter-btn {
            background: white;
            border: 1px solid #ff0000ff;
            color: #ff0000ff;
            padding: 8px 16px;
            border-radius: 20px;
            margin: 5px;
            transition: all 0.3s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #ff2a00ff;
            color: white;
        }
        .search-box {
            position: relative;
            max-width: 400px;
            margin: 0 auto 20px;
        }
        .search-box input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 1rem;
        }
        .search-box input:focus {
            outline: none;
            border-color:  #d32f2f;
        }
        .search-box .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #d32f2f;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid p-3">
            <h2>Order Food</h2>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                <a href="{{ route('customer.cart.index') }}" class="btn btn-primary">View Cart</a>
                <a href="{{ route('customer.cart.index') }}" class="btn btn-success">Checkout</a>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari menu...">
                <i class="fas fa-search search-icon"></i>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="text-center">
                    <button class="filter-btn active" data-category="all">Semua Menu</button>
                    @foreach($categories as $category)
                    <button class="filter-btn" data-category="{{ $category }}">{{ $category }}</button>
                    @endforeach
                </div>
            </div>

            @if($menus && count($menus) > 0)
                <div class="row">
                    @foreach($menus as $item)
                    <div class="col-md-4 menu-item-wrapper" data-category="{{ $item['category'] }}" data-name="{{ $item['name'] }}">
                        <div class="menu-item d-flex {{ $item['stock'] <= 0 ? 'opacity-50' : '' }}">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="menu-image mr-3 {{ $item['stock'] <= 0 ? 'grayscale' : '' }}">
                            <div class="flex-grow-1">
                                <h5>{{ $item['name'] }}</h5>
                                <p class="text-muted">{{ $item['description'] }}</p>
                                <p class="font-weight-bold text-primary">Rp {{ number_format($item['price']) }}</p>
                                @if($item['stock'] <= 0)
                                    <div class="mb-2">
                                        <span class="badge bg-danger">Stok Habis</span>
                                    </div>
                                @endif
                                @if($item['stock'] > 0)
                                    <form action="{{ route('customer.cart.add', $item['id']) }}" method="POST" class="form-inline">
                                        @csrf
                                        <div class="form-group mr-2">
                                            <label for="quantity-{{ $item['id'] }}" class="sr-only">Quantity</label>
                                            <input type="number" name="quantity" id="quantity-{{ $item['id'] }}" value="1" min="1" max="{{ $item['stock'] }}" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="form-group mr-2">
                                            <label for="notes-{{ $item['id'] }}" class="sr-only">Notes</label>
                                            <input type="text" name="notes" id="notes-{{ $item['id'] }}" placeholder="Notes (optional)" maxlength="500" class="form-control form-control-sm">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Order</button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-times"></i> Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p>No menu items available at this time.</p>
            @endif
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
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Filter functionality
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                const category = $(this).data('category');
                const searchTerm = $('#searchInput').val().toLowerCase();

                $('.menu-item-wrapper').each(function() {
                    const itemCategory = $(this).data('category');
                    const itemName = $(this).data('name').toLowerCase();
                    const matchesCategory = category === 'all' || itemCategory === category;
                    const matchesSearch = itemName.includes(searchTerm);

                    if (matchesCategory && matchesSearch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                updateNoResultsMessage();
            });

            // Search functionality
            $('#searchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                const activeCategory = $('.filter-btn.active').data('category');

                $('.menu-item-wrapper').each(function() {
                    const itemCategory = $(this).data('category');
                    const itemName = $(this).data('name').toLowerCase();
                    const matchesCategory = activeCategory === 'all' || itemCategory === activeCategory;
                    const matchesSearch = itemName.includes(searchTerm);

                    if (matchesCategory && matchesSearch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                updateNoResultsMessage();
            });

            function updateNoResultsMessage() {
                const visibleItems = $('.menu-item-wrapper:visible').length;
                let noResultsDiv = $('.no-results');

                if (visibleItems === 0) {
                    if (noResultsDiv.length === 0) {
                        $('.row').after('<div class="no-results"><i class="fas fa-search"></i><h3>Tidak ada menu ditemukan</h3><p>Coba kata kunci atau kategori lain</p></div>');
                    }
                    $('.no-results').show();
                } else {
                    $('.no-results').hide();
                }
            }
        });
    </script>
</body>

</html>
