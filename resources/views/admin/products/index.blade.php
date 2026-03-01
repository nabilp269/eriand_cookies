<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .btn-primary {
            background: var(--secondary);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 90, 43, 0.4);
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: white;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(90deg, var(--secondary) 0%, var(--primary) 100%);
            color: white;
            padding: 20px;
            border: none;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: bold;
        }
        
        .table thead th {
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            border: none;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f0f0f0;
        }
        
        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .product-img:hover {
            transform: scale(1.1);
        }
        
        .price-box {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6b35 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 3px 10px rgba(255, 107, 53, 0.4);
        }
        
        .btn-warning {
            background: #ffc107;
            border: none;
            border-radius: 15px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            background: #ff9800;
            transform: scale(1.05);
        }
        
        .btn-danger {
            border-radius: 15px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: scale(1.05);
        }
        
        .alert-success {
            border-radius: 15px;
            border: none;
            background: linear-gradient(90deg, #56ab2f 0%, #a8e063 100%);
            color: white;
            padding: 15px 20px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #999;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .table {
                font-size: 0.85rem;
            }
            
            .product-img {
                width: 50px;
                height: 50px;
            }
            
            .btn-sm {
                padding: 5px 10px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-cookie-bite text-warning"></i> KueKeringUMKM
            </a>
            <div class="d-flex gap-2">
                <a href="/admin/products" class="btn btn-warning btn-sm">
                    <i class="fas fa-box"></i> Produk
                </a>
                <a href="/admin/orders" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-shopping-bag"></i> Pesanan
                </a>
                <a href="/" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-home"></i> Web
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Header Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3><i class="fas fa-cookies"></i> Kelola Produk Kue</h3>
                    <small class="text-white-50">Kue Kering Eriand Cookies - Kualitas terbaik, harga terjangkau, langsung jadi!</small>
                </div>
                <a href="/admin/products/create" class="btn btn-light text-dark">
                    <i class="fas fa-plus-circle"></i> Tambah Produk
                </a>
            </div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="card">
            <div class="card-body p-0">
                @if(count($products) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">No</th>
                                    <th class="text-center" style="width: 100px;">Gambar</th>
                                    <th>Nama Kue</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $index => $p)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <img src="{{ asset('images/'.$p->gambar) }}" 
                                             class="product-img" 
                                             alt="{{ $p->nama_kue }}"
                                             onerror="this.src='https://via.placeholder.com/70?text=No+Image'">
                                    </td>
                                    <td>
                                        <strong>{{ $p->nama_kue }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($p->deskripsi, 50) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="price-box">
                                            <i class="fas fa-tag"></i> Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/products/{{ $p->id }}/edit" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="/admin/products/{{ $p->id }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Yakin hapus produk {{ $p->nama_kue }}?')" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-cookie-bite"></i>
                        <h5 class="mt- ada produk</h3">Belum5>
                        <p>Klik "Tambah Produk" untuk menambahkan kue kering</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="text-center mt-4 text-muted">
            <small>
                <i class="fas fa-cookie-bite text-warning"></i> 
                Kue Kering Eriand Cookies - Kualitas terbaik, harga terjangkau, langsung jadi!
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>