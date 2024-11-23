<?php
namespace App\Http\Controllers;

use App\Models\User; // Pastikan User model diimpor
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan semua pengguna
    public function index()
    {
        $users = User::with('role')->get();  // Ambil semua pengguna beserta role mereka
        return view('users.index', compact('users')); // Ganti customers menjadi users
    }
    

    // Menghapus user
    public function destroy($id)
{
    $user = User::findOrFail($id);

    // Cek jika pengguna yang akan dihapus adalah 'Owner'
    if ($user->role && $user->role->role_name === 'Owner') {
        return back()->with('error', 'Admin tidak dapat menghapus Owner.');
    }

    // Hapus user
    $user->delete();

    // Kembali ke halaman sebelumnya
    return back()->with('success', 'User berhasil dihapus.');
}

}
