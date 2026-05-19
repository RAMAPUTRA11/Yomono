<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek jika ada pembelian tertunda
            return $this->handlePendingPurchase();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Tampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role
        ]);

        Auth::login($user);

        // Cek jika ada pembelian tertunda (Buy It Now)
        return $this->handlePendingPurchase();
    }

    // Logika untuk menangani Buy It Now yang disimpan di session
    protected function handlePendingPurchase()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('home');
        }

        // 1. Cek apakah ada data "Buy Now" di session
        if (session()->has('pending_buy_now')) {
            $data = session()->get('pending_buy_now');

            // Masukkan ke database cart
            Cart::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_variant_id' => $data['product_variant_id']
                ],
                [
                    // Jika data baru dibuat, gunakan qty session. Jika update, tambah qty yang sudah ada.
                    'quantity' => DB::raw("COALESCE(quantity, 0) + " . (int)$data['quantity'])
                ]
            );

            session()->forget('pending_buy_now');
            
            return redirect()->route('cart.index')
                ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        }

        // 2. Jika tidak ada pending purchase, arahkan berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // --- GOOGLE OAUTH ---

    public function redirectToGoogle() 
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update jika user sudah ada tapi belum punya google_id
                $user->update(['google_id' => $googleUser->id]);
                Auth::login($user);
            } else {
                // Buat user baru
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password'  => null, 
                    'role'      => 'customer',
                ]);
                Auth::login($user);
            }

            // PERBAIKAN: Gunakan handlePendingPurchase agar produk Buy Now masuk ke Cart
            return $this->handlePendingPurchase();

        } catch (\Exception $e) {
            return "Login Error: " . $e->getMessage();
        }
    }
}