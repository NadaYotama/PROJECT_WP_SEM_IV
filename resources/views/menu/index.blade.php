<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Rumah Makan Padang Sabang Jaya</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/jpeg" sizes="16x16" href="{{ asset('assets/images/logorumahmakan.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #d32f2f;
            --secondary-color: #8b0000;
            --gold-color: #ffd700;
            --dark-color: #1a1a1a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            padding-top: 80px;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--gold-color) !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }

        .nav-link {
            color: white !important;
            margin: 0 10px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: var(--gold-color) !important;
        }

        .btn-order {
            background: var(--gold-color);
            color: var(--dark-color);
            font-weight: bold;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-order:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        /* Header Section */
        .page-header {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('/assets/images/foto_background.jpg') center/cover;            padding: 100px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }

        .page-header h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
        }

        .page-header p {
            font-size: 1.3rem;
            opacity: 0.9;
        }

        /* Filter Section */
        .filter-section {
            background: #f8f9fa;
            padding: 30px 0;
            margin-bottom: 50px;
            border-radius: 15px;
        }

        .filter-btn {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 10px 25px;
            border-radius: 25px;
            margin: 5px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        .search-box {
            position: relative;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .search-box input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #ddd;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(211, 47, 47, 0.2);
        }

        .search-box .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        /* Menu Card */
        .menu-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
            background: white;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .menu-card img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            transition: all 0.3s;
        }

        .menu-card:hover img {
            transform: scale(1.1);
        }

        .menu-card .card-img-wrapper {
            overflow: hidden;
            position: relative;
        }

        .menu-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            z-index: 2;
        }

        .menu-card .card-body {
            padding: 25px;
        }

        .menu-card h5 {
            color: var(--dark-color);
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .menu-card .description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 15px;
            min-height: 45px;
        }

        .menu-price {
            color: var(--primary-color);
            font-size: 1.6rem;
            font-weight: bold;
        }

        .btn-add-cart {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-add-cart:hover {
            background: var(--secondary-color);
            transform: scale(1.05);
        }

        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 15px;
        }

        /* Cart Summary */
        .cart-summary {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary-color);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 25px rgba(211, 47, 47, 0.5);
            cursor: pointer;
            transition: all 0.3s;
            z-index: 1000;
        }

        .cart-summary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.6);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--gold-color);
            color: var(--dark-color);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }

        /* Footer */
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 50px 0 20px;
            margin-top: 100px;
        }

        .footer h5 {
            color: var(--gold-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .footer-logo {
            height: 30px;
            width: auto;
            margin-right: 10px;
        }

        .footer a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer a:hover {
            color: var(--gold-color);
        }

        /* WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 100px;
            right: 30px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            background-color: #128C7E;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .filter-btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .cart-summary {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/images/logorumahmakan.jpg') }}" alt="Logo Rumah Makan Padang" class="navbar-logo">
                RM Padang Sabang Jaya
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#booking">Reservasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-order" href="/login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 data-aos="fade-up">Menu Kami</h1>
            <p data-aos="fade-up" data-aos-delay="200">Jelajahi berbagai hidangan lezat khas Padang</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Search Box -->
        <div class="search-box" data-aos="fade-up">
            <input type="text" id="searchInput" placeholder="Cari menu favorit Anda...">
            <i class="fas fa-search search-icon"></i>
        </div>

        <!-- Filter Section -->
        <div class="filter-section" data-aos="fade-up" data-aos-delay="100">
            <div class="text-center">
                <button class="filter-btn active" data-category="all">
                    <i class="fas fa-th"></i> Semua Menu
                </button>
                @foreach($categories as $category)
                <button class="filter-btn" data-category="{{ $category }}">
                    <i class="fas fa-utensils"></i> {{ $category }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="row g-4" id="menuGrid">
            @forelse($menus as $menu)
            <div class="col-lg-3 col-md-4 col-sm-6 menu-item" data-category="{{ $menu['category'] }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="card menu-card">
                    <div class="card-img-wrapper">
                        <span class="menu-badge">{{ $menu['category'] }}</span>
                    <img src="{{ $menu->image_url }}" alt="{{ $menu['name'] }}">
                    </div>
                    <div class="card-body">
                        <h5 class="menu-name">{{ $menu['name'] }}</h5>
                        <p class="description">{{ $menu['description'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="menu-price">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                            @if($menu['is_available'])
                            <button class="btn btn-add-cart" data-id="{{ $menu['id'] }}" data-name="{{ $menu['name'] }}" data-price="{{ $menu['price'] }}">
                                <i class="fas fa-cart-plus"></i> Pesan
                            </button>
                            @else
                            <button class="btn btn-add-cart" disabled>
                                <i class="fas fa-times"></i> Habis
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <h3>Belum ada menu tersedia</h3>
                    <p class="text-muted">Silakan cek kembali nanti</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Empty Search Result -->
        <div class="empty-state" id="emptySearch" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>Menu tidak ditemukan</h3>
            <p class="text-muted">Coba kata kunci lain</p>
        </div>
    </div>

    <!-- Cart Summary -->
    <div class="cart-summary" id="cartSummary" style="display: none;">
        <i class="fas fa-shopping-cart"></i>
        <span class="ms-2">Keranjang</span>
        <span class="cart-badge" id="cartCount">0</span>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><img src="{{ asset('assets/images/logorumahmakan.jpg') }}" alt="Logo Rumah Makan Padang" class="footer-logo"> Rumah Makan Padang</h5>
                    <h5>Sabang Jaya</h5>
                    <p class="text-white">Menyajikan masakan Padang autentik dengan resep turun temurun dan bahan berkualitas terbaik.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Raya Padang No. 123, Jakarta</p>
                    <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@rmpadang.com</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/">Home</a></li>
                        <li class="mb-2"><a href="/menu">Menu</a></li>
                        <li class="mb-2"><a href="/#booking">Reservasi</a></li>
                        <li class="mb-2"><a href="/login">Login</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center mt-3">
                <p class="text-white mb-0">&copy; 2025 Rumah Makan Padang. All Rights Reserved By DAMN Group.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/628123456789" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        let cart = [];

        // Filter functionality
        const filterBtns = document.querySelectorAll('.filter-btn');
        const menuItems = document.querySelectorAll('.menu-item');
        const menuGrid = document.getElementById('menuGrid');
        const emptySearch = document.getElementById('emptySearch');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

                const category = this.getAttribute('data-category');
                let visibleCount = 0;

                menuItems.forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide empty state
                menuGrid.style.display = visibleCount > 0 ? 'flex' : 'none';
                emptySearch.style.display = visibleCount > 0 ? 'none' : 'block';

                // Refresh AOS to animate newly visible items
                AOS.refresh();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            menuItems.forEach(item => {
                const menuName = item.querySelector('.menu-name').textContent.toLowerCase();
                const category = item.getAttribute('data-category').toLowerCase();
                
                if (menuName.includes(searchTerm) || category.includes(searchTerm)) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide empty state
            menuGrid.style.display = visibleCount > 0 ? 'flex' : 'none';
            emptySearch.style.display = visibleCount > 0 ? 'none' : 'block';

            // Reset filter buttons if searching
            if (searchTerm) {
                filterBtns.forEach(btn => btn.classList.remove('active'));
            }
        });

        // Add to cart functionality
        const addCartBtns = document.querySelectorAll('.btn-add-cart:not([disabled])');
        const cartSummary = document.getElementById('cartSummary');
        const cartCount = document.getElementById('cartCount');

        addCartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');

                // Check if user is logged in (you can modify this logic)
                // For now, it will redirect to login
                alert('Silakan login terlebih dahulu untuk melakukan pemesanan!');
                window.location.href = '/login';

                // If logged in, uncomment below:
                /*
                cart.push({ id, name, price });
                updateCart();
                
                // Show success feedback
                this.innerHTML = '<i class="fas fa-check"></i> Ditambahkan';
                this.style.background = '#28a745';
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-cart-plus"></i> Pesan';
                    this.style.background = '';
                }, 1000);
                */
            });
        });

        function updateCart() {
            if (cart.length > 0) {
                cartSummary.style.display = 'flex';
                cartCount.textContent = cart.length;
            } else {
                cartSummary.style.display = 'none';
            }
        }

        // Cart summary click
        cartSummary.addEventListener('click', function() {
            // Redirect to cart page or show cart modal
            window.location.href = '/cart';
        });
    </script>
</body>
</html>