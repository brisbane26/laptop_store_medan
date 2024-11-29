<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        View::share('title', 'Tambah Admin'); // Title default untuk semua method di AdminController
    }

    public function index()
    {
        $admins = User::where('role_id', Role::ADMIN_ID)->get();
        return view('admin.list_admin', compact('admins'));
    }

    public function create()
    {
        $title = 'Tambah Admin';
        return view('admin.tambah_admin', compact('title'));
    }

    public function store(Request $request)
    {
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

        $imagePath = $request->hasFile('image') 
            ? $request->file('image')->store('profile', 'public') 
            : null;

        User::create([
            'fullname' => $validatedData['fullname'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role_id' => Role::ADMIN_ID,
            'image' => $imagePath,
            'phone' => $validatedData['phone'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'coupon' => $validatedData['coupon'],
            'point' => $validatedData['point'],
        ]);

        return redirect()->route('admin.create')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = User::where('role_id', Role::ADMIN_ID)->findOrFail($id);
        return view('admin.edit_admin', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        \Log::info("Update Method Called for Admin ID: $id", $request->all()); // Log semua data request
    
        $admin = User::where('role_id', Role::ADMIN_ID)->findOrFail($id);
        \Log::info("Admin Found", $admin->toArray()); // Log data admin yang ditemukan
    
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'gender' => 'required|string',
            'address' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        \Log::info("Validated Data", $validatedData); // Log data yang telah divalidasi
    
        $admin->fill($validatedData);
    
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
            \Log::info("Password Updated");
        }
    
        if ($request->hasFile('image')) {
            if ($admin->image) {
                Storage::disk('public')->delete($admin->image);
                \Log::info("Old Image Deleted", ['path' => $admin->image]);
            }
    
            $imagePath = $request->file('image')->store('profile', 'public');
            $admin->image = $imagePath;
            \Log::info("New Image Stored", ['path' => $imagePath]);
        }
    
        if ($admin->isDirty()) {
            try {
                $admin->save();
                \Log::info("Admin Updated Successfully", $admin->toArray());
                return redirect()->route('admin.edit', $id)->with('success', 'Admin berhasil diperbarui.');
            } catch (\Exception $e) {
                \Log::error("Failed to Update Admin", ['error' => $e->getMessage()]);
                return redirect()->route('admin.edit', $id)->with('error', 'Terjadi kesalahan saat memperbarui admin.');
            }
        } else {
            \Log::warning("No Changes Detected for Admin ID: $id");
            return redirect()->route('admin.edit', $id)->with('error', 'Tidak ada perubahan yang terdeteksi.');
        }
    }
    
}
