<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'nama_kue' => 'Kue Nastar',
                'deskripsi' => 'Kue kering rasa nanas dengan taburan keju lezat.',
                'harga' => 100000,
                'gambar' => 'public/images/kue nastar.jpeg'
            ],
            [
                'nama_kue' => 'Kue Kastengel',
                'deskripsi' => 'Kue kering butter dengan taburan keju cheddar.',
                'harga' => 100000,
                'gambar' => 'public/images/kue kastengel.jpeg'
            ],
            [
                'nama_kue' => 'Kue Putri Salju',
                'deskripsi' => 'Kue lembut berlapis gula halus.',
                'harga' => 100000,
                'gambar' => 'public/images/kue putrisalju.jpeg'
            ],
            [
                'nama_kue' => 'Kue Palmchees',
                'deskripsi' => 'Kue ketan拉 terbaru yang legit.',
                'harga' => 80000,
                'gambar' => 'public/images/kue palmcheese.jpeg'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}