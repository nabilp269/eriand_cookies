<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Kue Kering Lezat - Eriand Cookies</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSRF Token Meta (WAJIB ADA agar bisa POST) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        :root {
            --primary-color: #5D4037;
            --secondary-color: #D7CCC8;
            --accent-color: #FFB74D;
            --text-color: #3E2723;
            --bg-light: #FFF8E1;
        }

        body {
            font-family: 'Nunito', sans-serif;
            color: var(--text-color);
            background-color: #fff;
        }

        h1, h2, h3, h4, h5, .navbar-brand, .btn, .section-title {
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .navbar {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 600;
            margin-left: 15px;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--accent-color);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(62, 39, 35, 0.6), rgba(62, 39, 35, 0.4)), 
                        url('images/full kue.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-top: 60px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            text-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .btn-hero {
            background-color: var(--accent-color);
            color: var(--text-color);
            font-weight: 700;
            padding: 12px 35px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            background-color: #FFA726;
            transform: translateY(-3px);
            color: var(--text-color);
        }

        /* Section Styles */
        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        /* Product Card */
        .product-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(93, 64, 55, 0.15);
        }
        
        .product-img-wrapper {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        
        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .product-card:hover .product-img-wrapper img {
            transform: scale(1.15);
        }

        .card-body-product {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .product-desc {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 20px;
            flex-grow: 1;
            line-height: 1.6;
        }

        .price-tag {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
        }

        .btn-tambah {
            background: linear-gradient(135deg, #5D4037, #8D6E63);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-tambah:hover {
            background: linear-gradient(135deg, #4E342E, #795548);
            transform: scale(1.02);
            color: white;
            box-shadow: 0 5px 15px rgba(93, 64, 55, 0.3);
        }

        /* About Section */
        .about-section {
            background-color: var(--bg-light);
            border-radius: 30px;
            margin: 80px auto;
            width: 90%;
        }

        /* Footer */
        footer { 
            background: var(--primary-color); 
            color: white; 
            padding: 60px 0 20px; 
        }
        
        .footer-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        .social-icon {
            width: 45px;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            margin: 0 8px;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .social-icon:hover { 
            background: var(--accent-color); 
            transform: translateY(-5px); 
            color: var(--text-color);
        }

        /* Loading Spinner */
        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .section-padding { padding: 50px 0; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fas fa-cookie-bite text-warning me-2 fs-4"></i> 
            Eriand<span class="text-dark">Cookies</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="#produk">Katalog</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                
                <!-- Keranjang -->
                <li class="nav-item ms-3">
                    <a href="/cart" class="btn btn-outline-dark position-relative p-2 px-3 rounded-pill">
                        <i class="fas fa-shopping-cart"></i> 
                        @php 
                            $cart = session()->get('cart', []); 
                            $cartCount = array_sum(array_column($cart, 'quantity'));
                        @endphp
                        @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </li>
                
                <!-- Menu Login/Logout -->
                @auth
                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item ms-2">
                            <a href="/admin/products" class="btn btn-dark rounded-pill px-3">
                                <i class="fas fa-cog"></i> Dashboard
                            </a>
                        </li>
                    @endif
                    <li class="nav-item ms-2">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-pill">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-2">
                        <a href="/auth/google" class="btn btn-danger rounded-pill">
                            <i class="fab fa-google"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="text-center">
        <h1 class="hero-title">Kue Kering Lezat<br>Eriand Cookies</h1>
        <p class="hero-subtitle">Kualitas terbaik untuk momen istimewa Anda</p>
        <a href="#produk" class="btn btn-hero mt-3">
            <i class="fas fa-arrow-down me-2"></i> Lihat Katalog
        </a>
    </div>
</section>

<!-- Produk Section -->
<section id="produk" class="py-5" style="background: #fafafa;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">Produk Unggulan</h2>
            <p class="text-muted">Pilih kue kering favorit Anda</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse($products as $p)
            <div class="col-md-6 col-lg-3">
                <div class="product-card">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/'.$p->gambar) }}" alt="{{ $p->nama_kue }}" onerror="this.src='https://via.placeholder.com/300x220?text=No+Image'">
                    </div>
                    <div class="card-body-product">
                        <h5 class="product-title">{{ $p->nama_kue }}</h5>
                        <p class="product-desc">{{ $p->deskripsi }}</p>
                        <span class="price-tag">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                        <button class="btn btn-tambah" onclick="addToCart({{ $p->id }}, this)">
                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Produk belum tersedia saat ini.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Tentang Section -->
<section id="tentang" class="section-padding">
    <div class="container">
        <div class="row align-items-center about-section p-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1517433670267-30f41c0f94b5?w=600" 
                     class="img-fluid rounded-3 shadow" alt="Tentang Kami">
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Tentang Eriand Cookies</h2>
                <p class="text-muted">Kami adalah UMKM yang memproduksi kue kering berkualitas tinggi dengan bahan-bahan pilihan dan resep turun-temurun.</p>
                <div class="row mt-4">
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">100%</h5>
                                <small class="text-muted">Bahan Alami</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">4.9/5</h5>
                                <small class="text-muted">Rating Pelanggan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="footer-title">
                    <i class="fas fa-cookie-bite text-warning me-2"></i> Eriand Cookies
                </div>
                <p class="text-muted">Menghasilkan kue kering terbaik untuk momen special Anda.</p>
            </div>
            <div class="col-lg-4 mb-4">
                <h5>Link Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="#produk" class="text-decoration-none text-muted d-block py-1">Katalog Produk</a></li>
                    <li><a href="#tentang" class="text-decoration-none text-muted d-block py-1">Tentang Kami</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5>Hubungi Kami</h5>
                <div class="mt-3">
                    <a href="#" class="social-icon text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon text-white"><i class="fab fa-facebook-f
                                        <a href="#" class="social-icon text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon text-white"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-icon text-white"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-light mt-4">
        <p class="text-center mb-0">&copy; 2024 Eriand Cookies. All rights reserved.</p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi Tambah ke Keranjang
    function addToCart(productId, btnElement) {
        // Loading state
        const originalText = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
        btnElement.classList.add('btn-loading');

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id: productId })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    // Refresh halaman atau update UI keranjang
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan'
                });
                btnElement.innerHTML = originalText;
                btnElement.classList.remove('btn-loading');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'Terjadi kesalahan koneksi';
            
            if (error.response) {
                errorMessage = error.response.data.message || errorMessage;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: errorMessage
            });
            btnElement.innerHTML = originalText;
            btnElement.classList.remove('btn-loading');
        });
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('shadow-sm');
        } else {
            navbar.classList.remove('shadow-sm');
        }
    });
</script>

</body>
</html>