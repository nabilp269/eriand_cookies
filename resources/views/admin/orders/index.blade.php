<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - KueKeringUMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .table-responsive { overflow-x: auto; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-cookie-bite text-warning"></i> KueKeringUMKM
            </a>
            <div>
                <a href="/admin/products" class="btn btn-outline-light">Produk</a>
                <a href="/admin/orders" class="btn btn-warning">Pesanan</a>
                <a href="/" class="btn btn-outline-light">Website</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4"><i class="fas fa-shopping-bag"></i> Kelola Pesanan</h3>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('wa_link'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><i class="fab fa-whatsapp"></i> Notifikasi WhatsApp siap dikirim!</strong>
                <a href="{{ session('wa_link') }}" target="_blank" class="btn btn-success btn-sm ms-2">
                    <i class="fab fa-whatsapp"></i> Kirim Sekarang
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if(count($orders) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Pemesan</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Pengiriman</th>
                                <th>Status Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <strong>{{ $order->nama_pemesan }}</strong><br>
                                    <small class="text-muted">{{ $order->telepon }}</small><br>
                                    <small class="text-muted">{{ $order->alamat }}</small>
                                </td>
                                <td>
                                    @foreach($order->items as $item)
                                        <span class="badge bg-light text-dark">
                                            {{ $item->product->nama_kue }} x{{ $item->jumlah }}
                                        </span><br>
                                    @endforeach
                                </td>
                                <td><strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($order->metode_pembayaran == 'cod')
                                        <span class="badge bg-warning text-dark">COD</span>
                                    @else
                                        <span class="badge bg-primary">Transfer</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="/admin/orders/{{ $order->id }}/update" method="POST" class="d-inline">
                                        @csrf
                                        <select name="pengiriman" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                            <option value="diantar" {{ $order->status_pengiriman == 'diantar' ? 'selected' : '' }}>🚚 Diantar</option>
                                            <option value="diambil" {{ $order->status_pengiriman == 'diambil' ? 'selected' : '' }}>🏪 Ambil Sendiri</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if($order->status_pesanan == 'diproses')
                                        <span class="badge bg-warning text-dark">⏳ Diproses</span>
                                    @elseif($order->status_pesanan == 'selesai')
                                        <span class="badge bg-success">✅ Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="/admin/orders/{{ $order->id }}/update" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status_pesanan" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                            <option value="diproses" {{ $order->status_pesanan == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="selesai" {{ $order->status_pesanan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada pesanan</h5>
                    <p class="text-muted">Pesanan akan muncul di sini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>