<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    // Menampilkan form tambah admin
    public function create()
{
    $title = 'Tambah Admin';
    return view('admin.tambah_admin', compact('title'));
}

public function __construct()
    {
        View::share('title', 'Tambah Admin');  // Title ini akan berlaku untuk semua method di AdminController
    }
    // Menyimpan data admin baru
    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'required|string|max:15',
            'gender' => 'required|string',
            'address' => 'required|string',
            'coupon' => 'required|integer',
            'point' => 'required|integer',
        ]);

        // Menyimpan file gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile', 'public');
        } else {
            $imagePath = null; // Jika tidak ada gambar, simpan null
        }

        // Menyimpan data user (admin)
        $user = User::create([
            'fullname' => $validatedData['fullname'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role_id' => Role::ADMIN_ID,  // Tetapkan role_id secara otomatis ke Admin (1)
            'image' => $imagePath,  // Menyimpan path gambar jika ada
            'phone' => $validatedData['phone'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'coupon' => $validatedData['coupon'],
            'point' => $validatedData['point'],
        ]);

        // Redirect atau pesan sukses
        return redirect()->route('admin.create')->with('success', 'Admin added successfully');
    }
}
