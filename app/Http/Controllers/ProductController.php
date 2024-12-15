<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
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
    
        return view('product.index', compact("title", "product", "search", "category"));
    }
      

    public function getProductData($id)
    {
        $product = Product::find($id);

        return $product;
    }


    public function addProductGet()
    {
        $title = "Add Product";

        return view('/product/add_product', compact("title"));
    }


    public function addProductPost(Request $request)
    {
        // Validasi input
        try {
            $validatedData = $request->validate([
                "product_name" => "required|max:25",
                "category" => "required|in:new_laptop,second_laptop,others", // Hanya nilai dropdown
                "orientation" => "required",
                "description" => "required",
                "buy_price" => "required|numeric|gt:0",
                "sell_price" => "required|numeric|gt:0|gte:buy_price",
                "stock" => "required|numeric|gt:0",
                "discount" => "nullable|numeric|gt:0|lt:100",
                "image" => "image|max:2048|mimes:jpeg,png,jpg", // Tipe file ditentukan
            ]);
    
            // Pastikan discount memiliki nilai default jika tidak diisi
            $validatedData['discount'] = $validatedData['discount'] ?? 0;
    
            \Log::info("Validation passed", ['validatedData' => $validatedData]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error("Validation failed", ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        }
    
        // Default image handling
        try {
            if (!isset($validatedData["image"])) {
                $validatedData["image"] = env("IMAGE_PRODUCT");
            } else {
                if (!$request->file("image")->isValid()) {
                    \Log::error("Invalid image file", ['image' => $request->file("image")->getClientOriginalName()]);
                    return back()->withErrors(['image' => 'Failed to upload image.']);
                }
                $validatedData["image"] = $request->file("image")->store("product");
                \Log::info("Image uploaded successfully", ['image_path' => $validatedData["image"]]);
            }
        } catch (\Exception $e) {
            \Log::error("Image handling failed", ['message' => $e->getMessage()]);
            return back()->withErrors(['image' => 'Failed to handle image.']);
        }
    
        try {
            // Menambahkan 'created_by' ke dalam validated data
            $validatedData['created_by'] = auth()->user()->id; // Mendapatkan ID pengguna yang sedang login
    
            // Panggil stored procedure
            \Log::info("Calling stored procedure", ['procedure' => 'add_product_procedure', 'params' => $validatedData]);
    
            DB::select('CALL add_product_procedure(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $validatedData['product_name'],
                $validatedData['category'],
                $validatedData['orientation'],
                $validatedData['description'],
                $validatedData['buy_price'],
                $validatedData['sell_price'],
                $validatedData['stock'],
                $validatedData['discount'],
                $validatedData['image'],
                $validatedData['created_by'], // Parameter created_by ditambahkan
            ]);
    
            \Log::info("Stored procedure executed successfully");
    
            // Tampilkan pesan sukses
            $message = "Product has been added!";
            myFlasherBuilder(message: $message, success: true);
    
            return redirect('/product');
        } catch (\Illuminate\Database\QueryException $exception) {
            // Tangani error database
            \Log::error("Database error", [
                'message' => $exception->getMessage(),
                'sql' => $exception->getSql(),
                'bindings' => $exception->getBindings(),
            ]);
            return back()->withErrors(['error' => 'Failed to add product. Please try again later.']);
        } catch (\Exception $e) {
            \Log::error("Unexpected error", ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }    

    public function editProductGet(Product $product)
    {
        $data["title"] = "Edit Product";
        $data["product"] = $product;

        return view("/product/edit_product", $data);
    }


    public function editProductPost(Request $request, Product $product)
{
    $rules = [
        'category' => 'required|in:new_laptop,second_laptop,others',
        'orientation' => 'required',
        'description' => 'required',
        'buy_price' => 'required|numeric|gt:0',
        'sell_price' => 'required|numeric|gt:0',
        'stock' => 'required|numeric|gt:0',
        'discount' => 'nullable|numeric|gte:0|lt:100',
        'image' => 'image|file|max:2048'
    ];

    if ($product->product_name != $request->product_name) {
        $rules['product_name'] = 'required|max:255|unique:products,product_name';
    } else {
        $rules['product_name'] = 'required|max:255';
    }

    $validatedData = $request->validate($rules);

    // Set default discount if not provided
    $validatedData['discount'] = $validatedData['discount'] ?? 0;

    $validatedData['updated_by'] = auth()->id(); // Set updated_by with authenticated user ID

    try {
        if ($request->file('image')) {
            if ($request->oldImage && $request->oldImage != env("IMAGE_PRODUCT")) {
                Storage::delete($request->oldImage); // Delete old image if exists
            }
            $validatedData['image'] = $request->file('image')->store('product');
        }

        $product->fill($validatedData);

        if ($product->isDirty()) {
            $product->save();
            myFlasherBuilder(message: 'Product has been updated!', success: true);
            return redirect('/product');
        } else {
            myFlasherBuilder(message: 'Action <strong>failed</strong>, no changes detected!', failed: true);
            return back();
        }
    } catch (\Illuminate\Database\QueryException $exception) {
        return abort(500); // Handle database error
    }
} 
}

class LandingController extends Controller
{
    public function index()
    {
        $product = app(ProductController::class)->getAllProducts(); // Memanggil method dari ProductController
        return view('landing.index', compact('product'));
    }
}

