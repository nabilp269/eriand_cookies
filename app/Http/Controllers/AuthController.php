<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    // ================= GOOGLE LOGIN (USER) =================

    /**
     * Redirect ke Google Login - Paksa Pilih Akun
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->with([
                    'prompt' => 'select_account', // WAJIB PILIH AKUN
                    'access_type' => 'offline',
                ])
                ->redirect();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Gagal membuka Google Login. Silakan coba lagi!');
        }
    }

    /**
     * Handle Google Callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data dari Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Buat user baru jika belum ada
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'User',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()), // Random password
                    'role' => 'user',
                ]);
            } else {
                // Update google_id dan avatar jika user sudah ada
                $user->google_id = $googleUser->getId();
                $user->avatar = $googleUser->getAvatar();
                $user->save();
            }
            
            // Login user
            Auth::login($user);
            
            // Regenerate session untuk keamanan
            $request = request();
            $request->session()->regenerate();
            
            return redirect('/')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Google Login Error: ' . $e->getMessage());
            
            return redirect('/')
                ->with('error', 'Login Google gagal! Error: ' . $e->getMessage());
        }
    }

    // ================= ADMIN LOGIN =================

    /**
     * Tampilkan Form Login Admin
     */
    public function showAdminLoginForm()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/products');
        }
        
        return view('admin.login');
    }

    /**
     * Handle Admin Login
     */
    public function adminLogin(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'password.required' => 'Password wajib diisi!',
        ]);

        // Coba login
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Cek apakah user adalah admin
            if ($user->role === 'admin') {
                // Regenerate session
                $request->session()->regenerate();
                
                return redirect('/admin/products')
                    ->with('success', 'Login admin berhasil! Selamat datang, ' . $user->name);
            } else {
                // Bukan admin, logout dan tampilkan error
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect('/admin/login')
                    ->with('error', 'Akses ditolak! Email ini bukan akun admin.');
            }
        }

        // Login gagal
        return redirect('/admin/login')
            ->with('error', 'Email atau password salah!')
            ->withInput($request->except('password'));
    }

    // ================= LOGOUT =================

    /**
     * Logout - INI YANG PENTING!
     */
    public function logout(Request $request)
    {
        // 1. Ambil nama user sebelum logout (untuk pesan)
        $userName = Auth::user()->name ?? 'User';
        
        // 2. Logout dari Laravel
        Auth::logout();
        
        // 3. Hapus semua session data
        $request->session()->invalidate();
        
        // 4. Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // 5. Hapus semua flash data
        Session::flush();
        
        // 6. Redirect ke home dengan pesan
        return redirect('/')
            ->with('success', 'Logout berhasil! Terima kasih, ' . $userName);
    }

    // ================= HELPER METHODS =================

    /**
     * Cek apakah user sudah login
     */
    public function checkAuth()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role,
                    'avatar' => Auth::user()->avatar,
                ]
            ]);
        }
        
        return response()->json([
            'authenticated' => false
        ]);
    }

    /**
     * Login manual (untuk testing)
     */
    public function manualLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            
            return redirect('/')->with('success', 'Login berhasil!');
        }

        return redirect('/')->with('error', 'Login gagal!');
    }
}