<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom yang mungkin belum ada
            if (!Schema::hasColumn('orders', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable();
            }
            if (!Schema::hasColumn('orders', 'status_pengiriman')) {
                $table->string('status_pengiriman')->default('diantar');
            }
            if (!Schema::hasColumn('orders', 'status_pesanan')) {
                $table->string('status_pesanan')->default('diproses');
            }
        });
    }

    public function down()
    {
        //
    }
};