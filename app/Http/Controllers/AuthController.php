<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ========== USER GOOGLE LOGIN ==========
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user sudah ada
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // User baru - daftar otomatis
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'user',
                ]);
            } else {
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
            }
            
            // Login user
            Auth::login($user);
            return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
            
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Login dengan Google gagal!');
        }
    }

    // ========== ADMIN LOGIN ==========

    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user dengan role admin
        $user = User::where('email', $credentials['email'])
                    ->where('role', 'admin')
                    ->first();

        if ($user && \Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect('/admin/products')->with('success', 'Welcome Admin!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // ========== LOGOUT ==========

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Berhasil logout');
    }
}