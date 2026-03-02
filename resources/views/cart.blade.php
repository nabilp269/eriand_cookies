<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - KueKeringUMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #d4a574;
            --secondary: #8b5a2b;
            --dark: #2c1810;
            --light: #faf3e8;
        }
        
        body {
            background: linear-gradient(135deg, #faf3e8 0%, #e8d5b7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--dark) 0%, var(--secondary) 100%) !important;
        }
        
        .cart-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            background: white;
        }
        
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        
        .product-img:hover {
            transform: scale(1.1);
        }
        
        .form-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: white;
            position: sticky;
            top: 100px;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(139, 90, 43, 0.25);
        }
        
        .price-box {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5253 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .btn-checkout {
            background: linear-gradient(45deg, var(--secondary) 0%, var(--primary) 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(139, 90, 43, 0.4);
            color: white;
        }
        
        .payment-option {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover, .payment-option.active {
            border-color: var(--secondary);
            background: rgba(212, 165, 116, 0.1);
        }
        
        .payment-option input {
            display: none;
        }
        
        .user-info-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .bukti-transfer-box {
            border: 2px dashed var(--secondary);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            background: rgba(212, 165, 116, 0.1);
            transition: all 0.3s ease;
        }
        
        .bukti-transfer-box:hover {
            background: rgba(212, 165, 116, 0.2);
        }
        
        .bukti-transfer-box input[type="file"] {
            display: none;
        }
        
        .bukti-transfer-box label {
            cursor: pointer;
        }
        
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }
        
        .empty-cart i {
            font-size: 6rem;
            color: #ddd;
        }
        
        @media (max-width: 768px) {
            .product-img {
                width: 60px;
                height: 60px;
            }
            
            .form-card {
                position: static;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-cookie-bite text-warning"></i> KueKeringUMKM
            </a>
            <a href="/" class="btn btn-outline-light rounded-pill">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container pb-5">
        <h3 class="mb-4">
            <i class="fas fa-shopping-cart"></i> Keranjang Belanja
        </h3>
        
        @php
            $cart = session()->get('cart', []);
            $total = 0;
        @endphp
        
        @if(count($cart) > 0)
            <div class="row">
                <!-- Daftar Produk -->
                <div class="col-lg-8 mb-4">
                    @foreach($cart as $id => $item)
                    <div class="card cart-card mb-3 shadow-sm">
                        <div class="card-body d-flex align-items-center flex-wrap gap-3">
                            <img src="{{ asset('images/'.$item['gambar']) }}" 
                                 class="product-img" 
                                 alt="{{ $item['nama_kue'] }}">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $item['nama_kue'] }}</h5>
                                <p class="text-muted mb-0">
                                    Rp {{ number_format($item['harga'], 0, ',', '.') }} / pcs
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary fs-6">
                                    {{ $item['quantity'] }} pcs
                                </span>
                            </div>
                            <div class="text-end">
                                <strong>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                    @php $total += $item['harga'] * $item['quantity']; @endphp
                    @endforeach
                </div>
                
                <!-- Form Checkout -->
                <div class="col-lg-4">
                    <div class="card form-card shadow-lg border-0">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-4">
                                <i class="fas fa-clipboard-list"></i> Form Pemesanan
                            </h5>
                            
                            <!-- Info User Login -->
                            @auth
                                <div class="user-info-badge mb-3">
                                    <i class="fas fa-user-circle"></i>
                                    Login sebagai: {{ Auth::user()->name }}
                                </div>
                            @endauth
                            
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            
                            <!-- Form Checkout - WAJIB LOGIN -->
                            @auth
                                <form action="/checkout" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <!-- Nama Pemesan -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-user"></i> Nama Pemesan
                                        </label>
                                        <input type="text" 
                                               name="nama" 
                                               class="form-control" 
                                               value="{{ Auth::user()->name }}" 
                                               placeholder="Nama lengkap"
                                               required>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Bisa diubah sesuai kebutuhan
                                        </small>
                                    </div>
                                    
                                    <!-- Telepon -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-phone"></i> No. Telepon
                                        </label>
                                        <input type="text" 
                                               name="telepon" 
                                               class="form-control" 
                                               placeholder="0812xxxxxxx"
                                               required>
                                        <small class="text-muted">Untuk notifikasi WhatsApp</small>
                                    </div>
                                    
                                    <!-- Alamat -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-map-marker-alt"></i> Alamat Lengkap
                                        </label>
                                        <textarea name="alamat" 
                                                  class="form-control" 
                                                  rows="3" 
                                                  placeholder="JL. Contoh No.123, Kota"
                                                  required></textarea>
                                    </div>
                                    
                                    <!-- Metode Pembayaran -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-credit-card"></i> Metode Pembayaran
                                        </label>
                                        
                                        <!-- Transfer Bank -->
                                        <label class="payment-option d-block mb-2" id="option_transfer">
                                            <input type="radio" name="metode_pembayaran" value="transfer" 
                                                   id="payment_transfer"
                                                   onchange="toggleBuktiTransfer()" required>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-university fa-lg text-primary me-3" style="width: 30px;"></i>
                                                <div>
                                                    <strong>Transfer Bank</strong>
                                                    <small class="d-block text-muted">BCA: 1234567890</small>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <!-- Bukti Transfer (Muncul Jika Transfer) -->
                                        <div id="bukti_transfer_section" class="mt-2" style="display: none;">
                                            <label class="form-label">
                                                <i class="fas fa-camera"></i> Upload Bukti Transfer
                                            </label>
                                            <div class="bukti-transfer-box" onclick="document.getElementById('bukti_input').click()">
                                                <input type="file" 
                                                       name="bukti_transfer" 
                                                       id="bukti_input"
                                                       accept="image/*"
                                                       onchange="previewBuktiTransfer(this)">
                                                <div id="bukti_preview_text">
                                                    <i class="fas fa-cloud-upload-alt fa-2x text-secondary mb-2"></i>
                                                    <p class="mb-0 text-secondary">Klik untuk upload bukti transfer</p>
                                                    <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                                                </div>
                                                <img id="bukti_preview_img" 
                                                     style="display: none; max-width: 100%; border-radius: 10px; margin-top: 10px;" 
                                                     alt="Preview Bukti Transfer">
                                            </div>
                                        </div>
                                        
                                        <!-- COD -->
                                        <label class="payment-option d-block" id="option_cod">
                                            <input type="radio" name="metode_pembayaran" value="cod" 
                                                   id="payment_cod"
                                                   onchange="toggleBuktiTransfer()" required>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave fa-lg text-success me-3" style="width: 30px;"></i>
                                                <div>
                                                    <strong>COD</strong>
                                                    <small class="d-block text-muted">Bayar di tempat</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Metode Pengiriman -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-shipping-fast"></i> Metode Pengiriman
                                        </label>
                                        
                                        <label class="payment-option d-block mb-2">
                                            <input type="radio" name="pengiriman" value="diantar" checked required>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-motorcycle fa-lg text-danger me-3" style="width: 30px;"></i>
                                                <div>
                                                    <strong>Diantar</strong>
                                                    <small class="d-block text-muted">Kirim ke alamat</small>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <label class="payment-option d-block">
                                            <input type="radio" name="pengiriman" value="diambil" required>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-store fa-lg text-warning me-3" style="width: 30px;"></i>
                                                <div>
                                                    <strong>Ambil Sendiri</strong>
                                                    <small class="d-block text-muted">Ambil di toko</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Catatan -->
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-sticky-note"></i> Catatan (Opsional)
                                        </label>
                                        <textarea name="catatan" 
                                                  class="form-control" 
                                                  placeholder="Contoh: Tingkat manis sedang"></textarea>
                                    </div>
                                    
                                    <hr>
                                    
                                    <!-- Ringkasan -->
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Total Items</span>
                                        <span class="text-muted">{{ count($cart) }} jenis</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="fw-bold">Total Bayar</span>
                                        <span class="price-box">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-checkout text-white w-100">
                                        <i class="fas fa-paper-plane"></i> Kirim Pesanan
                                    </button>
                                </form>
                            @else
                                <!-- Jika Belum Login -->
                                <div class="text-center py-4">
                                    <i class="fas fa-user-lock fa-4x text-muted mb-3"></i>
                                    <h5 class="mb-3">Login Required</h5>
                                    <p class="text-muted mb-4">
                                        Silakan login dengan Google terlebih dahulu untuk checkout
                                    </p>
                                    
                                    <a href="/auth/google" class="btn btn-danger btn-lg rounded-pill mb-3">
                                        <i class="fab fa-google"></i> Login dengan Google
                                    </a>
                                    
                                    <hr>
                                    
                                    <p class="text-muted small mb-0">
                                        atau <a href="/admin/login">login sebagai admin</a>
                                    </p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Keranjang Kosong -->
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <h4 class="text-muted mt-3">Keranjang kamu masih kosong</h4>
                <p class="text-muted">Yuk, pilih kue kering favoritmu!</p>
                <a href="/" class="btn btn-primary btn-lg rounded-pill mt-3">
                    <i class="fas fa-store"></i> Belanja Sekarang
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Highlight payment option yang dipilih
        document.querySelectorAll('.payment-option input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Hapus class active dari semua option dengan name sama
                document.querySelectorAll(`input[name="${this.name}"]`).forEach(r => {
                    r.closest('.payment-option').classList.remove('active');
                });
                // Tambah class active ke option yang dipilih
                this.closest('.payment-option').classList.add('active');
            });
        });

        // Trigger change pada radio yang sudah checked saat load
        document.querySelectorAll('.payment-option input[type="radio"]:checked').forEach(radio => {
            radio.closest('.payment-option').classList.add('active');
        });

        // Toggle Bukti Transfer
        function toggleBuktiTransfer() {
            const transferOption = document.getElementById('payment_transfer');
            const buktiSection = document.getElementById('bukti_transfer_section');
            
            if(transferOption.checked) {
                buktiSection.style.display = 'block';
            } else {
                buktiSection.style.display = 'none';
            }
        }

        // Preview Bukti Transfer
        function previewBuktiTransfer(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bukti_preview_img').src = e.target.result;
                    document.getElementById('bukti_preview_img').style.display = 'block';
                    document.getElementById('bukti_preview_text').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>

