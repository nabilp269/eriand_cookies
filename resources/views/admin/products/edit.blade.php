<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - KueKeringUMKM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/admin/products">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-5">
                        <h3 class="mb-4"><i class="fas fa-edit"></i> Edit Produk</h3>
                        
                        <form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Kue</label>
                                <input type="text" name="nama_kue" class="form-control" value="{{ $product->nama_kue }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" required>{{ $product->deskripsi }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control" value="{{ $product->harga }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Gambar Produk</label>
                                <br>
                                <img src="{{ asset('images/'.$product->gambar) }}" width="150" class="mb-2 rounded">
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="/admin/products" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>