<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    // Konstruktor untuk middleware autentikasi dan pembatasan role
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya user yang sudah login yang bisa mengakses
        $this->middleware('can:is_admin')->only('adminIndex'); // Hanya admin yang bisa mengakses adminIndex
    }

    // Menampilkan halaman layanan jika sudah login
    public function index()
    {
        if (Auth::guest()) {
            return redirect()->route('login')->with('message', 'You must be logged in to access this page.');
        }

        $services = Service::where('status', '!=', 'done')->get(); 
        $title = "Service Bookings";

        // Jika role user adalah admin, arahkan ke tampilan admin
        if (auth()->user()->role->role_name == 'admin') {
            return view('admin.services.index', compact('services', 'title'));
        }

        // Jika role user adalah customer, arahkan ke tampilan customer
        return view('service.index', compact('services','title'));    
    }

    public function adminIndex()
{
    // Mengambil semua service untuk admin
    $services = Service::all();
    $title = "Service Requests";
    return view('service.servis_data', compact('services', 'title'));
}


    public function showData()
{
    // Logika khusus untuk halaman servis data
    $title = "Service Data";
    $services = Service::all();
    return view('service.servis_data', compact('services', 'title'));
}

    // Menyimpan booking service
    public function store(Request $request)
    {
        // Validasi input
        $validatedData=$request->validate([
            'laptop_model' => 'required|string|max:255', // Pastikan laptop_model ada
            'problem_description' => 'required|string', // Pastikan problem_description ada
        ]);
    
        // Cek apakah pengguna sudah login
        if (auth()->check()) {
            // Menyimpan data service request
            Service::create([
                'user_id' => auth()->id(),
                'laptop_model' => $validatedData['laptop_model'],
                'problem_description' =>$validatedData['problem_description'],
            ]);
    
            // Redirect kembali dengan pesan sukses
            return redirect()->route('services.index')->with('success', 'Service request created successfully.');
        } 
    }
    
    // Menyetujui request service
    public function approve(Request $request, Service $service)
{
    // Validasi harga
    $validatedData=$request->validate([
        'price' => 'required|numeric|min:0', // Harga harus valid
    ]);

    // Periksa apakah status saat ini sudah 'pending' sebelum disetujui
    if ($service->status !== 'pending') {
        return back()->with('error', 'This service request cannot be approved.');
    }

    // Update data service
    $service->update([
        'price' =>$validatedData['price'],
        'status' => 'approved', // Status service menjadi 'approved'
    ]);

    // Redirect ke halaman daftar service dengan pesan sukses
    return back()->with('success', 'Service approved successfully!');
}

    // Mengupdate status service
    public function updateStatus(Request $request, Service $service)
{
    // Validasi status yang diterima
    $validated = $request->validate([
        'status' => 'required|in:pending,in-progress,ready-to-pickup,done',
    ]);

    // Update status
    $service->status = $validated['status'];

    // Jika statusnya done, pastikan harga sudah ditetapkan
    if ($service->status == 'done' && !$service->price) {
        return back()->with('message', 'Please set the price before completing the service.');
    }

    $service->save();

    return back()->with('message', 'Service status updated successfully.');
}

public function history()
{
    if (auth()->user()->role_id === 1 || auth()->user()->role_id === 3) {
        $services = Service::with('user')  // Pastikan relasi user dimuat
            ->where('status', 'done')
            ->orderBy('updated_at', 'desc')
            ->get();
    } else {
        $services = Service::with('user')  // Pastikan relasi user dimuat
            ->where('user_id', auth()->id())
            ->where('status', 'done')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    $title = "Service History";
    return view('service.servis_history', compact('services', 'title'));
}


}