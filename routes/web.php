<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// ===================
// Routes Publik
// ===================
Route::get('/', [ProductController::class, 'index']);
Route::get('/cart', [ProductController::class, 'cart']);
Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');

// Checkout - WAJIB LOGIN
Route::post('/checkout', [ProductController::class, 'checkout'])->middleware('auth');

// ===================
// Authentication
// ===================

// Google Login (User)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Admin Login
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Logout - WAJIB POST!
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================
// Routes Admin
// ===================
Route::middleware(['auth'])->group(function () {
    
    // Cek role admin
    Route::get('/admin', function() {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return redirect('/admin/products');
    });
    
    // Produk
    Route::get('/admin/products', function() {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->adminIndex();
    });
    
    Route::get('/admin/products/create', function() {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->create();
    });
    
    Route::post('/admin/products', function(\Illuminate\Http\Request $request) {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->store($request);
    });
    
    Route::get('/admin/products/{id}/edit', function($id) {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->edit($id);
    });
    
    Route::put('/admin/products/{id}', function(\Illuminate\Http\Request $request, $id) {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->update($request, $id);
    });
    
    Route::delete('/admin/products/{id}', function($id) {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->destroy($id);
    });
    
    // Pesanan
    Route::get('/admin/orders', function() {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->orders();
    });
    
    Route::post('/admin/orders/{id}/update', function(\Illuminate\Http\Request $request, $id) {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return app(ProductController::class)->updateStatus($request, $id);
    });
});