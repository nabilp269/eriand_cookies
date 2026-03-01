<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'nama_pemesan',
        'telepon',
        'alamat',
        'catatan',
        'metode_pembayaran',
        'status_pesanan',
        'status_pengiriman',
        'bukti_transfer',
        'total_harga',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}