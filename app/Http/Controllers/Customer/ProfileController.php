<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Mengambil semua alamat milik user yang sedang login
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->get();

        return view('customer.profile_edit', compact('addresses'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    // Fungsi Baru: Menyimpan Alamat
    public function storeAddress(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'label' => 'required|string|max:50',
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
        ]);

        // Jika alamat ini diset jadi default atau merupakan alamat pertama, matikan default alamat lain
        $isFirst = $user->addresses()->count() == 0;
        $isDefault = $request->has('is_default') || $isFirst;

        if ($isDefault) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        UserAddress::create([
            'user_id' => Auth::id(),
            'label' => $request->label,
            'receiver_name' => $request->receiver_name,
            'phone_number' => $request->phone_number,
            'full_address' => $request->full_address,
            'is_default' => $isDefault,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    // Fungsi Baru: Menghapus Alamat
    public function destroyAddress($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        // Jika yang dihapus alamat utama, set salah satu alamat tersisa menjadi utama
        if ($address->is_default) {
            $nextAddress = UserAddress::where('user_id', Auth::id())->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('profile.edit')->with('success', 'Alamat berhasil dihapus.');
    }
}