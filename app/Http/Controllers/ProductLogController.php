<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductLog;
use Illuminate\Support\Facades\View; // Jangan lupa import View

class ProductLogController extends Controller
{
    public function __construct()
    {
        // Menambahkan title untuk setiap view yang dipanggil
        View::share('title', 'Product Logs'); // Sesuaikan dengan title yang diinginkan
    }

    public function index()
    {
        // Ambil semua data dari product_logs
        $logs = ProductLog::select('id', 'action', 'old_value', 'new_value', 'admin_name', 'date')->paginate(10);
        
        // Tampilkan ke halaman view
        return view('product_logs.index', compact('logs'));
    }
}
