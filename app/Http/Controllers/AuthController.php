<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if (session()->has('pending_buy_now')) {
            $data = session()->get('pending_buy_now');

            // Masukkan ke database cart
            Cart::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_variant_id' => $data['product_variant_id']
                ],
                ['quantity' => $data['quantity']]
            );

            session()->forget('pending_buy_now');
            
            // Langsung arahkan ke halaman cart/checkout
            return redirect()->route('cart.index')->with('success', 'Akun berhasil dibuat dan produk ditambahkan ke keranjang!');
        }

        // Jika tidak ada pending purchase, ke home atau dashboard admin
        if (Auth::user()->role === 'admin') {
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
}