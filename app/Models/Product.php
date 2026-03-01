<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    // Tambahkan ini:
    protected $fillable = [
        'nama_kue',
        'deskripsi',
        'harga',
        'gambar',
    ];
}