<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // user yang pesan
            $table->string('nama_pemesan');
            $table->string('telepon');
            $table->text('alamat');
            $table->text('catatan')->nullable();
            $table->string('metode_pembayaran'); // 'transfer' atau 'cod'
            $table->string('status_pesanan')->default('diproses'); // diproses, selesai, diambil
            $table->string('status_pengiriman')->default('diantar'); // diantar, diambil sendiri
            $table->string('bukti_transfer')->nullable(); // filename
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};