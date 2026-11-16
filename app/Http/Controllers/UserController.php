<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh akun pengguna.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan akun pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|unique:users,username|alpha_dash',
            'nip'       => 'nullable|string|max:50',
            'role'      => 'required|in:superadmin,kasie,logistik,trc',
            'is_active' => 'required|boolean',
            'password'  => 'required|string|min:6',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan, silakan pilih username lain.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'role.required'       => 'Role / hak akses wajib dipilih.',
            'role.in'             => 'Pilihan role tidak valid.',
            'is_active.required'  => 'Status aktif wajib dipilih.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'nip'       => $request->nip,
            'role'      => $request->role,
            'is_active' => (bool) $request->is_active,
            'password'  => Hash::make($request->password),
        ]);

        ActivityLogger::log(
            'Tambah',
            'Manajemen Akun',
            "Menambahkan akun pengguna baru: {$user->name} (@{$user->username}) dengan role " . strtoupper($user->role) . "."
        );

        return redirect()->route('users.index')
                         ->with('success', "Akun pengguna '{$user->name}' (@{$user->username}) berhasil ditambahkan!");
    }

    /**
     * Menampilkan form edit akun pengguna.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data akun pengguna di database.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:100|alpha_dash|unique:users,username,' . $user->id,
            'nip'       => 'nullable|string|max:50',
            'role'      => 'required|in:superadmin,kasie,logistik,trc',
            'is_active' => 'required|boolean',
            'password'  => 'nullable|string|min:6',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan oleh akun lain.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'role.required'       => 'Role / hak akses wajib dipilih.',
            'role.in'             => 'Pilihan role tidak valid.',
            'is_active.required'  => 'Status aktif wajib dipilih.',
            'password.min'        => 'Password baru minimal terdiri dari 6 karakter.',
        ]);

        $dataUpdate = [
            'name'      => $request->name,
            'username'  => $request->username,
            'nip'       => $request->nip,
            'role'      => $request->role,
            'is_active' => (bool) $request->is_active,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataUpdate);

        // Jika superadmin mengedit profilnya sendiri, sinkronkan session
        if (Auth::id() === $user->id) {
            session([
                'nama_lengkap' => $user->name,
                'username'     => $user->username,
                'role'         => $user->role,
            ]);
        }

        ActivityLogger::log(
            'Ubah',
            'Manajemen Akun',
            "Memperbarui akun pengguna: {$user->name} (@{$user->username}) [Role: " . strtoupper($user->role) . ", Status: " . ($user->is_active ? 'Aktif' : 'Nonaktif') . "]."
        );

        return redirect()->route('users.index')
                         ->with('success', "Data akun '{$user->name}' berhasil diperbarui!");
    }

    /**
     * Menghapus akun pengguna (mencegah superadmin menghapus akunnya sendiri yang sedang login).
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')
                             ->with('error', 'GAGAL! Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        $nama     = $user->name;
        $username = $user->username;
        $role     = strtoupper($user->role);

        $user->delete();

        ActivityLogger::log(
            'Hapus',
            'Manajemen Akun',
            "Menghapus akun pengguna: {$nama} (@{$username}) [Role: {$role}]."
        );

        return redirect()->route('users.index')
                         ->with('success', "Akun pengguna '{$nama}' (@{$username}) berhasil dihapus!");
    }
}
