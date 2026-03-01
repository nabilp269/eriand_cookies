<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3>Tambah Produk Kue Kering</h3>
        <a href="/admin/products" class="btn btn-secondary mb-3">Kembali</a>

        <form action="/admin/products" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Nama Kue</label>
                <input type="text" name="nama_kue" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Simpan Produk</button>
        </form>
    </div>
</body>
</html>