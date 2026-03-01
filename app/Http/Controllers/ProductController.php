<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    // ================= USER =================

    public function index()
    {
        $products = Product::all();
        return view('home', compact('products'));
    }

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
        
        return response()->json(['success' => true, 'message' => 'Berhasil ditambahkan ke keranjang!']);
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong!');
        }
        
        $request->validate([
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
            'metode_pembayaran' => 'required',
            'pengiriman' => 'required',
        ]);
        
        $total = 0;
        foreach($cart as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
        
        $order = Order::create([
            'user_id' => auth()->id() ?? null,
            'nama_pemesan' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'catatan' => $request->catatan ?? '',
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pesanan' => 'diproses',
            'status_pengiriman' => $request->pengiriman,
            'total_harga' => $total,
        ]);
        
        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'jumlah' => $item['quantity'],
                'harga_saat_pesan' => $item['harga'],
            ]);
        }
        
        $request->session()->forget('cart');
        
        $waMessage = $this->buatPesanWAAdmin($order, $cart, $total);
        $waLinkAdmin = "https://wa.me/6281234567890?text=" . urlencode($waMessage);
        
        return redirect('/')->with('success', 'Pesanan berhasil dibuat! No. Pesanan: #' . $order->id)->with('wa_link', $waLinkAdmin);
    }

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
        $message .= "*Pengiriman:* " . ($order->status_pengiriman == 'diambil' ? 'Ambil Sendiri' : 'Diantar') . "\n\n";
        $message .= "*Pesanan:*\n{$items}\n";
        $message .= "*Total:* Rp " . number_format($total, 0, ',', '.') . "\n";
        $message .= "*Pembayaran:* " . ($order->metode_pembayaran == 'cod' ? 'COD' : 'Transfer') . "\n\n";
        $message .= "Mohon diproses! 🍪";
        
        return $message;
    }

    // ================= ADMIN =================

    public function adminIndex()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kue' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = time().'.'.$request->gambar->extension();  
        $request->gambar->move(public_path('images'), $imageName);

        Product::create([
            'nama_kue' => $request->nama_kue,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $imageName,
        ]);

        return redirect('/admin/products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kue' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if (file_exists(public_path('images/'.$product->gambar))) {
                unlink(public_path('images/'.$product->gambar));
            }
            
            $imageName = time().'.'.$request->gambar->extension();  
            $request->gambar->move(public_path('images'), $imageName);
            $product->gambar = $imageName;
        }

        $product->nama_kue = $request->nama_kue;
        $product->deskripsi = $request->deskripsi;
        $product->harga = $request->harga;
        $product->save();

        return redirect('/admin/products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if (file_exists(public_path('images/'.$product->gambar))) {
            unlink(public_path('images/'.$product->gambar));
        }
        
        $product->delete();
        return redirect('/admin/products')->with('success', 'Produk dihapus');
    }

    // ================= ADMIN - PESANAN =================

    public function orders()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // PERBAIKAN: updateStatus - jangan overwrite jika null
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
            // Jika masih kosong, gunakan default
            $order->status_pengiriman = 'diantar';
        }
        
        $order->save();
        
        $waLink = $this->kirimNotifikasiWA($order);
        
        return back()->with('success', 'Status pesanan diperbarui!')->with('wa_link', $waLink);
    }

    private function kirimNotifikasiWA($order)
    {
        $items = "";
        foreach($order->items as $item) {
            $items .= "- {$item->product->nama_kue} x{$item->jumlah}\n";
        }
        
        $statusText = [
            'diproses' => 'Sedang Diproses 🍪',
            'selesai' => 'Sudah Jadi! 🎉',
        ];
        
        $pengirimanText = [
            'diantar' => 'Akan diantar ke alamat',
            'diambil' => 'Silakan ambil di toko',
        ];
        
        $message = "*Update Pesanan #{$order->id} - KueKeringUMKM*\n\n";
        $message .= "Halo {$order->nama_pemesan}! 👋\n\n";
        $message .= "📦 *Status Pesanan:* {$statusText[$order->status_pesanan]}\n";
        $message .= "🚚 *Pengiriman:* {$pengirimanText[$order->status_pengiriman]}\n\n";
        $message .= "📋 *Detail Pesanan:*\n{$items}\n";
        $message .= "💰 *Total:* Rp " . number_format($order->total_harga, 0, ',', '.') . "\n\n";
        
        if($order->status_pesanan == 'selesai') {
            if($order->status_pengiriman == 'diambil') {
                $message .= "Kue kamu sudah jadi! Silakan ambil di toko ya! 🏪\n";
            } else {
                $message .= "Kue kamu sudah jadi dan akan diantar segera! 🚀\n";
            }
            $message .= "Terima kasih! 🎂";
        } else {
            $message .= "Mohon ditunggu ya, kue sedang dibuat dengan cinta! 💕";
        }
        
        $waNumber = '62' . ltrim($order->telepon, '0');
        $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($message);
        
        return $waLink;
    }
}