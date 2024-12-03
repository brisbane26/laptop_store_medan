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

    // Filter produk berdasarkan input pencarian
    $search = $request->input('search');
    $product = Product::when($search, function ($query, $search) {
        return $query->where('product_name', 'like', '%' . $search . '%');
    })->get();

    return view('/product/index', compact("title", "product", "search"));
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
        $validatedData = $request->validate([
            "product_name" => "required|max:25",
            "stock" => "required|numeric|gt:0",
            "price" => "required|numeric|gt:0",
            "discount" => "required|numeric|gt:0|lt:100",
            "orientation" => "required",
            "description" => "required",
            "image" => "image|max:2048|mimes:jpeg,png,jpg", // Tipe file ditentukan
        ]);

        // Default image handling
        if (!isset($validatedData["image"])) {
            $validatedData["image"] = env("IMAGE_PRODUCT");
        } else {
            if (!$request->file("image")->isValid()) {
                return back()->withErrors(['image' => 'Failed to upload image.']);
            }
            $validatedData["image"] = $request->file("image")->store("product");
        }

        try {
            // Panggil stored procedure
            DB::select('CALL add_product_procedure(?, ?, ?, ?, ?, ?, ?)', [
                $validatedData['product_name'],
                $validatedData['stock'],
                $validatedData['price'],
                $validatedData['discount'],
                $validatedData['orientation'],
                $validatedData['description'],
                $validatedData['image'],
            ]);

            // Tampilkan pesan sukses
            $message = "Product has been added!";
            myFlasherBuilder(message: $message, success: true);

            return redirect('/product');
        } catch (\Illuminate\Database\QueryException $exception) {
            // Tangani error database
            \Log::error("Database error: " . $exception->getMessage());
            return back()->withErrors(['error' => 'Failed to add product. Please try again later.']);
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
            'orientation' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|gt:0',
            'stock' => 'required|numeric|gt:0',
            'discount' => 'required|numeric|gt:0|lt:100',
            'image' => 'image|file|max:2048'
        ];


        if ($product->product_name != $request->product_name) {
            $rules['product_name'] = 'required|max:25|unique:products,product_name';
        } else {
            $rules['product_name'] = 'required|max:25';
        }

        $validatedData = $request->validate($rules);

        try {
            if ($request->file("image")) {
                if ($request->oldImage != env("IMAGE_PRODUCT")) {
                    Storage::delete($request->oldImage);
                }

                $validatedData["image"] = $request->file("image")->store("product");
            }

            $product->fill($validatedData);


            if ($product->isDirty()) {
                $product->save();

                $message = "Product has been updated!";

                myFlasherBuilder(message: $message, success: true);
                return redirect("/product");
            } else {
                $message = "Action <strong>failed</strong>, no changes detected!";

                myFlasherBuilder(message: $message, failed: true);
                return back();
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return abort(500);
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

