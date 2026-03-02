<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ================= USER =================

    /**
     * Halaman Utama - Tampilkan Semua Produk
     */
    public function index()
    {
        $products = Product::all();
        return view('home', compact('products'));
    }

    /**
     * Tambah Produk ke Keranjang (AJAX)
     */
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->id);
        
        $cart = session()->get('cart', []);
        
        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'nama_kue' => $product->nama_kue,
                'harga' => $product->harga,
                'gambar' => $product->gambar,
                'quantity' => 1
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true, 
            'message' => 'Berhasil ditambahkan ke keranjang!',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Halaman Keranjang
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    /**
     * Checkout - Simpan Pesanan
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Validasi keranjang tidak kosong
        if(empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong!');
        }
        
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|in:transfer,cod',
            'pengiriman' => 'required|in:diantar,diambil',
        ]);
        
        // Jika Transfer, wajib upload bukti transfer
        if($request->metode_pembayaran == 'transfer') {
            $request->validate([
                'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }
        
        // Hitung total harga
        $total = 0;
        foreach($cart as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
        
        // Upload bukti transfer jika ada
        $buktiTransfer = null;
        if($request->hasFile('bukti_transfer')) {
            $buktiTransfer = time().'.'.$request->bukti_transfer->extension();
            $request->bukti_transfer->move(public_path('bukti_transfer'), $buktiTransfer);
        }
        
        // Simpan pesanan ke database
        $order = Order::create([
            'user_id' => auth()->id() ?? null,
            'nama_pemesan' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'catatan' => $request->catatan ?? '',
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_transfer' => $buktiTransfer,
            'status_pesanan' => 'diproses',
            'status_pengiriman' => $request->pengiriman,
            'total_harga' => $total,
        ]);
        
        // Simpan detail item pesanan
        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'jumlah' => $item['quantity'],
                'harga_saat_pesan' => $item['harga'],
            ]);
        }
        
        // Bersihkan keranjang
        $request->session()->forget('cart');
        
        // Kirim notifikasi WhatsApp ke admin
        $waMessage = $this->buatPesanWAAdmin($order, $cart, $total);
        $waLinkAdmin = "https://wa.me/6281234567890?text=" . urlencode($waMessage);
        
        return redirect('/')
            ->with('success', 'Pesanan berhasil dibuat! No. Pesanan: #' . $order->id)
            ->with('wa_link', $waLinkAdmin);
    }

    /**
     * Buat Pesan WhatsApp untuk Admin
     */
    private function buatPesanWAAdmin($order, $cart, $total)
    {
        $items = "";
        foreach($cart as $item) {
            $items .= "- {$item['nama_kue']} x{$item['quantity']} = Rp " . number_format($item['harga'] * $item['quantity'], 0, ',', '.') . "\n";
        }
        
        $message = "*PESANAN BARU #{$order->id} - KueKeringUMKM*\n\n";
        $message .= "*Nama:* {$order->nama_pemesan}\n";
        $message .= "*Telepon:* {$order->telepon}\n";
        $message .= "*Alamat:* {$order->alamat}\n";
        $message .= "*Pengiriman:* " . ($order->status_pengiriman == 'diambil' ? 'Ambil Sendiri' : 'Diantar') . "\n";
        $message .= "*Pembayaran:* " . ($order->metode_pembayaran == 'cod' ? 'COD' : 'Transfer') . "\n";
        if($order->bukti_transfer) {
            $message .= "*Bukti Transfer:* " . url('bukti_transfer/'.$order->bukti_transfer) . "\n";
        }
        $message .= "\n*Pesanan:*\n{$items}";
        $message .= "\n*Total:* Rp " . number_format($total, 0, ',', '.') . "\n\n";
        $message .= "Mohon diproses! 🍪";
        
        return $message;
    }

    // ================= ADMIN =================

    /**
     * Dashboard Admin - Kelola Produk
     */
    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Form Tambah Produk
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Simpan Produk Baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kue' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload gambar
        $imageName = time().'.'.$request->gambar->extension();  
        $request->gambar->move(public_path('images'), $imageName);

        // Simpan ke database
        Product::create([
            'nama_kue' => $request->nama_kue,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $imageName,
        ]);

        return redirect('/admin/products')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Form Edit Produk
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update Produk
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_kue' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Jika ada gambar baru, hapus yang lama dan upload yang baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if (file_exists(public_path('images/'.$product->gambar))) {
                unlink(public_path('images/'.$product->gambar));
            }
            
            // Upload gambar baru
            $imageName = time().'.'.$request->gambar->extension();  
            $request->gambar->move(public_path('images'), $imageName);
            $product->gambar = $imageName;
        }

        // Update data produk
        $product->nama_kue = $request->nama_kue;
        $product->deskripsi = $request->deskripsi;
        $product->harga = $request->harga;
        $product->save();

        return redirect('/admin/products')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus Produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus gambar dari folder
        if (file_exists(public_path('images/'.$product->gambar))) {
            unlink(public_path('images/'.$product->gambar));
        }
        
        // Hapus dari database
        $product->delete();
        
        return redirect('/admin/products')->with('success', 'Produk dihapus!');
    }

    // ================= ADMIN - PESANAN =================

    /**
     * Halaman Kelola Pesanan
     */
    public function orders()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update Status Pesanan + Kirim WhatsApp ke Pembeli
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Update status pesanan
        if ($request->has('status_pesanan')) {
            $order->status_pesanan = $request->status_pesanan;
        }
        
        // Update pengiriman - hanya jika ada nilai baru
        if ($request->has('pengiriman') && $request->pengiriman !== null) {
            $order->status_pengiriman = $request->pengiriman;
        } else if (empty($order->status_pengiriman)) {
            $order->status_pengiriman = 'diantar';
        }
        
        $order->save();
        
        // Kirim notifikasi WhatsApp ke pembeli
        $waLink = $this->kirimNotifikasiWA($order);
        
        return back()
            ->with('success', 'Status pesanan diperbarui!')
            ->with('wa_link', $waLink);
    }

    /**
     * Kirim Notifikasi WhatsApp ke Pembeli
     */
    private function kirimNotifikasiWA($order)
    {
        $items = "";
        foreach($order->items as $item) {
            $items .= "- {$item->product->nama_kue} x{$item->jumlah}\n";
        }
        
        // Status pesan
        if($order->status_pesanan == 'selesai') {
            $status = "✅ Sudah Jadi";
        } else {
            $status = "⏳ Sedang Diproses";
        }
        
        // Pengiriman pesan
        if($order->status_pengiriman == 'diambil') {
            $pengiriman = "Ambil di Toko";
        } else {
            $pengiriman = "Diantar ke Alamat";
        }
        
        // Pesan yang sopan dan simpel
        $message = "Halo {$order->nama_pemesan}! 👋\n\n";
        $message .= "Terima kasih sudah memesan di KueKeringUMKM! 😊\n\n";
        $message .= "*Status Pesanan: {$status}*\n";
        $message .= "Pengiriman: {$pengiriman}\n\n";
        $message .= "*Detail Pesanan:*\n{$items}";
        $message .= "Total: Rp " . number_format($order->total_harga, 0, ',', '.') . "\n\n";
        
        if($order->status_pesanan == 'selesai') {
            if($order->status_pengiriman == 'diambil') {
                $message .= "Kue kamu sudah bisa diambil di toko ya! 🏪\n";
                $message .= "Alamat toko: Jl. Kebonagung No.45, Surabaya\n\n";
            } else {
                $message .= "Kue kamu akan diantar segera! 🚀\n\n";
            }
            $message .= "Terima kasih! Selamat menikmati 🍪";
        } else {
            $message .= "Kue kamu sedang dibuat dengan cinta 💕\n";
            $message .= "Mohon ditunggu ya!";
        }
        
        // Format nomor WA (62xxx bukan 0xxx)
        $waNumber = '62' . ltrim($order->telepon, '0');
        $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($message);
        
        return $waLink;
    }
}