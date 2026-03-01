<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - KueKeringUMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .cart-card { border: none; border-radius: 15px; overflow: hidden; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-cookie-bite text-warning"></i> KueKeringUMKM
            </a>
            <a href="/" class="btn btn-outline-light"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </nav>

    <div class="container py-5">
        <h3 class="mb-4"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h3>
        
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
                        <div class="card-body d-flex align-items-center">
                            <img src="{{ asset('images/'.$item['gambar']) }}" width="80" height="80" class="rounded" style="object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h5 class="mb-1">{{ $item['nama_kue'] }}</h5>
                                <p class="text-muted mb-0">Rp {{ number_format($item['harga'], 0, ',', '.') }} / pcs</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary fs-6">{{ $item['quantity'] }} pcs</span>
                            </div>
                            <div class="ms-3 text-end">
                                <strong>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                    @php $total += $item['harga'] * $item['quantity']; @endphp
                    @endforeach
                </div>
                
                <!-- Form Checkout -->
                <div class="col-lg-4">
                    <div class="card cart-card shadow-lg border-0">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-4">📝 Form Pemesanan</h5>
                            
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            
                            <form action="/checkout" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Pemesan</label>
                                    <input type="text" name="nama" class="form-control" required placeholder="Nama lengkap">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control" required placeholder="JL. Contoh No.123, Kota"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="telepon" class="form-control" required placeholder="0812xxxxxxx">
                                </div>
                                
                                <!-- Metode Pembayaran -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Metode Pembayaran</label>
                                    <div class="form-check border rounded p-3 mb-2">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="transfer" value="transfer" checked>
                                        <label class="form-check-label" for="transfer">
                                            <i class="fas fa-university text-primary"></i> Transfer Bank
                                        </label>
                                    </div>
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="cod">
                                        <label class="form-check-label" for="cod">
                                            <i class="fas fa-money-bill-wave text-success"></i> COD (Bayar di Tempat)
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Metode Pengiriman -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Metode Pengiriman</label>
                                    <div class="form-check border rounded p-3 mb-2">
                                        <input class="form-check-input" type="radio" name="pengiriman" id="diantar" value="diantar" checked>
                                        <label class="form-check-label" for="diantar">
                                            <i class="fas fa-motorcycle text-danger"></i> Diantar ke Alamat
                                        </label>
                                    </div>
                                    <div class="form-check border rounded p-3">
                                        <input class="form-check-input" type="radio" name="pengiriman" id="diambil" value="diambil">
                                        <label class="form-check-label" for="diambil">
                                            <i class="fas fa-store text-warning"></i> Ambil Sendiri (Pickup)
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Catatan (Opsional)</label>
                                    <textarea name="catatan" class="form-control" placeholder="Contoh: Tingkat manis sedang"></textarea>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Total Items</span>
                                    <span class="text-muted">{{ count($cart) }} jenis</span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-bold">Total Bayar</span>
                                    <span class="text-danger fw-bold fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100 btn-lg rounded-pill">
                                    <i class="fas fa-paper-plane"></i> Kirim Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Keranjang Kosong -->
            <div class="text-center py-5">
                <i class="fas fa-shopping-basket fa-5x text-muted mb-4"></i>
                <h4 class="text-muted">Keranjang kamu masih kosong</h4>
                <p class="text-muted">Yuk, pilih kue kering favoritmu!</p>
                <a href="/" class="btn btn-primary btn-lg rounded-pill mt-3">
                    <i class="fas fa-store"></i> Belanja Sekarang
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>