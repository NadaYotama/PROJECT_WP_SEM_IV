<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Makan Padang - Authentic Taste</title>
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
        }

        /* Navbar Styling */
        .navbar-custom {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-custom.scrolled {
            padding: 0.5rem 0;
            background: rgba(139, 0, 0, 0.95);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--gold-color) !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .nav-link {
            color: white !important;
            margin: 0 10px;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }

        .nav-link:hover {
            color: var(--gold-color) !important;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gold-color);
            transition: all 0.3s;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=1600') center/cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            z-index: 2;
        }

        .hero-section h1 {
            font-size: 4rem;
            font-weight: bold;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.5);
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-bottom: 30px;
            animation: fadeInUp 1.2s ease;
        }

        .hero-buttons .btn {
            margin: 10px;
            padding: 15px 35px;
            font-size: 1.1rem;
            border-radius: 30px;
            transition: all 0.3s;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(211, 47, 47, 0.4);
        }

        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            background: transparent;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-color);
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            transition: all 0.3s;
            text-align: center;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .feature-card h3 {
            color: var(--dark-color);
            margin-bottom: 15px;
            font-weight: 600;
        }

        /* Menu Section */
        .menu-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 3rem;
            color: var(--dark-color);
            font-weight: bold;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--primary-color);
        }

        .menu-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .menu-card img {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }

        .menu-card .card-body {
            padding: 25px;
        }

        .menu-card h5 {
            color: var(--dark-color);
            font-weight: bold;
            margin-bottom: 15px;
        }

        .menu-price {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: bold;
        }

        .btn-add-cart {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .btn-add-cart:hover {
            background: var(--secondary-color);
            transform: scale(1.05);
        }

        /* Booking Section */
        .booking-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
        }

        .booking-form {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .booking-form .form-control {
            background: rgba(255,255,255,0.9);
            border: none;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .btn-book {
            background: var(--gold-color);
            color: var(--dark-color);
            font-weight: bold;
            padding: 15px;
            border: none;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-book:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 20px rgba(255, 215, 0, 0.5);
        }

        /* Testimonials */
        .testimonials-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 3px solid var(--gold-color);
        }

        .rating {
            color: var(--gold-color);
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        /* Footer */
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 50px 0 20px;
        }

        .footer h5 {
            color: var(--gold-color);
            margin-bottom: 20px;
        }

        .footer a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer a:hover {
            color: var(--gold-color);
        }

        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: all 0.3s;
        }

        .social-icons a:hover {
            color: var(--gold-color);
            transform: translateY(-5px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating WhatsApp Button */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
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
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section p {
                font-size: 1.2rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-utensils"></i> RM Padang
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#booking">Reservasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-order" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer id="about" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-utensils"></i> Rumah Makan Padang</h5>
                    <p class="text-muted">Menyajikan masakan Padang autentik dengan resep turun temurun dan bahan berkualitas terbaik.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Raya Padang No. 123, Jakarta</p>
                    <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@rmpadang.com</p>
                    <p><i class="fas fa-clock"></i> Senin - Minggu: 08.00 - 22.00 WIB</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home">Home</a></li>
                        <li class="mb-2"><a href="#menu">Menu</a></li>
                        <li class="mb-2"><a href="#booking">Reservasi</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}">Login</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center mt-3">
                <p class="text-muted mb-0">&copy; 2024 Rumah Makan Padang. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
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

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add to cart functionality
        document.querySelectorAll('.btn-add-cart').forEach(button => {
            button.addEventListener('click', function() {
                // Store cart data (requires login)
                alert('Silakan login terlebih dahulu untuk melakukan pemesanan!');
                window.location.href = '{{ route("login") }}';
            });
        });
    </script>
</body>
</html>
