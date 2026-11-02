<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public const AREAS = ['Pengawas TPS', 'Panwaslu Kelurahan/Desa', 'Panwaslu Kecamatan', 'Bawaslu Kabupaten/Kota', 'Bawaslu Provinsi'];

    private function admin(): void
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Hanya admin yang dapat mengelola petugas.');
    }

    public function index()
    {
        $this->admin();

        return view('users.index', ['users' => User::orderBy('name')->get(), 'areas' => self::AREAS]);
    }

    public function store(Request $request)
    {
        $this->admin();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'area' => ['required', Rule::in(self::AREAS)],
            'role' => ['required', Rule::in(['admin', 'petugas'])],
        ]);

        User::forceCreate([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'area' => $data['area'],
            'role' => $data['role'],
        ]);

        return back()->with('ok', 'Akun petugas dibuat.');
    }

    public function destroy(User $user)
    {
        $this->admin();
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Anda tidak bisa menghapus akun sendiri.']);
        }
        $user->delete();

        return back()->with('ok', 'Akun dihapus.');
    }
}
