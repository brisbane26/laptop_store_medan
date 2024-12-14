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

        $services = Service::whereIn('status', ['pending', 'approved', 'in-progress', 'ready-to-pickup'])->get();
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
    $validatedData = $request->validate([
        'laptop_model' => 'required|string|max:255',
        'problem_description' => 'required|string',
        'equipments' => 'nullable|string',
        'laptop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        'order_date' => 'required|date',
    ]);

    if (auth()->check()) {
        try {
            // Simpan gambar jika ada
            $imagePath = null;
            if ($request->hasFile('laptop_image')) {
                $imagePath = $request->file('laptop_image')->store('images/laptops', 'public');
            }

            // Simpan data ke database
            Service::create([
                'user_id' => auth()->id(),
                'laptop_model' => $validatedData['laptop_model'],
                'equipments' => $validatedData['equipments'] ?? null,
                'problem_description' => $validatedData['problem_description'],
                'laptop_image' => $imagePath,
                'order_date' => $validatedData['order_date'],
                'status' => 'pending', // Default status
            ]);

            session()->flash('message', 'Your service request has been submitted successfully!');
            return redirect()->route('service.servis_data')->with('message', [
                'type' => 'success',
                'text' => 'Service request created successfully.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('services.index')->with('message', [
                'type' => 'error',
                'text' => 'Failed to create service request.',
            ]);
        }
    }
}

public function cancel($id)
{
    $service = Service::findOrFail($id);

    // Pastikan hanya customer yang dapat membatalkan dan status pending
    if (auth()->user()->role_id == 2 && $service->status == 'pending') {
        $service->delete();

        return redirect()->route('service.servis_data')->with('message', [
            'type' => 'success',
            'text' => 'Service request has been canceled and deleted successfully.',
        ]);
    }

    return redirect()->route('service.servis_data')->with('message', [
        'type' => 'error',
        'text' => 'You cannot cancel this service request.',
    ]);
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

public function reject(Request $request, $id)
{
    $service = Service::findOrFail($id); // Cari service berdasarkan ID
    $service->status = 'rejected'; // Ubah status menjadi rejected
    $service->rejection_reason = $request->input('reason'); // Simpan alasan penolakan
    $service->save(); // Simpan perubahan

    return redirect()->back()->with('message', '<div class="alert alert-success">Service rejected successfully!</div>');
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

    // Jika status menjadi 'ready-to-pickup', isi end_date dengan tanggal saat ini
    if ($validated['status'] === 'ready-to-pickup') {
        $service->end_date = now(); // Menggunakan helper Laravel untuk mendapatkan tanggal saat ini
    }

    // Jika statusnya 'done', pastikan harga sudah ditetapkan
    if ($validated['status'] === 'done' && !$service->price) {
        return back()->with('message', 'Please set the price before completing the service.');
    }

    $service->save();

    return back()->with('message', 'Service status updated successfully.');
}

public function history()
{
    if (auth()->user()->role_id === 1 || auth()->user()->role_id === 3) {
        $services = Service::with('user')  // Pastikan relasi user dimuat
            ->whereIn('status', ['done', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();
    } else {
        $services = Service::with('user')  // Pastikan relasi user dimuat
            ->where('user_id', auth()->id())
            ->whereIn('status', ['done', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    $title = "Service History";
    return view('service.servis_history', compact('services', 'title'));
}

public function downloadInvoice($id)
{
    // Cari data service berdasarkan ID
    $service = Service::findOrFail($id);

    // Load view untuk invoice
    $html = view('service.service_invoice', compact('service'))->render();

    // Konfigurasi Mpdf
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);

    // Download PDF
    $filename = 'Invoice_Service_' . $service->id . '.pdf';
    return response($mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}


}