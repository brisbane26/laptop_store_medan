<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandingController extends Controller{
    public function index(Request $request)
    {
        $title = "Product";
    
        // Input pencarian dan kategori
        $search = $request->input('search');
        $category = $request->input('category');
    
        // Query produk dengan filter pencarian dan kategori
        $product = DB::table('product_view')
            ->when($category, function ($query, $category) {
                // Ubah kategori dari dropdown ke string deskriptif
                $categoryMap = [
                    'new_laptop' => 'New Laptop',
                    'second_laptop' => 'Second Laptop',
                    'others' => 'Others',
                ];
    
                if (isset($categoryMap[$category])) {
                    return $query->where('category', $categoryMap[$category]);
                }
            })
            ->when($search, function ($query, $search) use ($category) {
                return $query->where('product_name', 'like', '%' . $search . '%');
            })
            ->get();
    
        return view('landing.index', compact("title", "product", "search", "category"));
    }

    public function getProductData($id)
    {
        $product = Product::find($id);

        return $product;
    }
}