<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $title = 'Your Cart'; // Title halaman
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

        return view('/cart/index', compact('title', 'cartItems'));
    }

    // Tambah barang ke keranjang
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
    
        $product = Product::find($productId);
    
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan!'], 404);
        }
    
        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $productId]
        );
        
        // Update quantity secara manual
        $cart->quantity += $quantity;
        $cart->save();        
    
        return response()->json(['message' => 'Barang berhasil ditambahkan ke keranjang!']);
    }    
    

    // Kurangi barang dari keranjang
    public function removeFromCart(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart && $cart->quantity > 1) {
            $cart->decrement('quantity'); // Kurangi kuantitas
        } else {
            $cart?->delete(); // Hapus jika quantity = 1
        }

        return redirect()->back()->with('success', 'Item updated');
    }

    // Hapus barang dari keranjang
    public function deleteFromCart(Request $request)
    {
        Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->delete();

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    // Checkout barang
    public function checkout()
    {
        $userId = auth()->id();
        $cartItems = Cart::where('user_id', $userId)->with('product')->get(); // Memuat relasi produk
        
        return view('/order/make_order', [
            'title' => 'Make Order',
            'cartItems' => $cartItems, // Kirimkan semua item di keranjang
        ]);
    }
    
}