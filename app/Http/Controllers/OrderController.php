<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Auth, Storage, Validator};
use App\Models\{Order, OrderDetail, Cart, Status, Product, Role, Transaction, User};
use Mpdf\Mpdf;

class OrderController extends Controller
{
    public function calculateTotalPrice($quantity, $price)
    {
        // Memanggil fungsi Total_Harga dari database
        $result = DB::select("SELECT Total_Harga(?, ?) AS total_price", [$quantity, $price]);

        // Mengembalikan hasil total harga
        return $result[0]->total_price;
    }
    
    public function makeOrderGet()
    {
        $userId = auth()->id();
        $cartItems = Cart::where('user_id', $userId)->with('product')->get(); // Memuat relasi produk
        
        return view('/order/make_order', [
            'title' => 'Make Order',
            'cartItems' => $cartItems, // Kirimkan semua item di keranjang
        ]);
    }
    
    public function makeOrderPost(Request $request)
    {
        $rules = [
            'address' => 'required|max:255',
            'payment_method' => 'required|numeric',
            'province' => 'required|numeric|gt:0',
            'city' => 'required|numeric|gt:0',
            'total_price' => 'required|gt:0',
            'shipping_address' => 'nullable|max:255',
            'coupon_used' => 'required|gte:0',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|numeric|min:1',
            'price.*' => 'required|numeric|min:0',
        ];
    
        $messages = [
            'product_id.*.required' => 'Product ID is required.',
            'quantity.*.min' => 'Quantity must be at least 1.',
            'price.*.min' => 'Price must be at least 0.',
            'bank_id.required' => 'Please select a valid bank.',
            'bank_id.exists' => 'The selected bank is invalid.',
        ];
    
        if ($request->payment_method == 1) {
            $rules['bank_id'] = 'required|numeric|exists:banks,id';
        }
    
        $validatedData = $request->validate($rules, $messages);
    
        foreach ($validatedData['product_id'] as $index => $productId) {
            $product = Product::findOrFail($productId);
            if ($product->stock < $validatedData['quantity'][$index]) {
                return back()->withErrors([
                    "quantity.{$index}" => "Quantity exceeds stock ({$product->stock}) for {$product->name}."
                ])->withInput();
            }
        }
    
        DB::transaction(function () use ($validatedData) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'address' => $validatedData['address'],
                'shipping_address' => $validatedData['shipping_address'],
                'total_price' => $validatedData['total_price'],
                'payment_id' => $validatedData['payment_method'],
                'note_id' => ($validatedData['payment_method'] == 1) ? 2 : 1,
                'status_id' => 2,
                'transaction_doc' => ($validatedData['payment_method'] == 1) ? env('IMAGE_PROOF') : null,
                'is_done' => 0,
                'coupon_used' => $validatedData['coupon_used'],
                'bank_id' => $validatedData['payment_method'] == 1 ? $validatedData['bank_id'] : null,
            ]);
    
            foreach ($validatedData['product_id'] as $index => $productId) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $validatedData['quantity'][$index],
                    'price' => $validatedData['price'][$index],
                ]);
    
                Product::where('id', $productId)->decrement('stock', $validatedData['quantity'][$index]);
            }
    
            Cart::where('user_id', auth()->id())->delete();
        });
    
    return redirect('/order/order_data')->with('success', 'Order successfully created!');
}


    public function orderData()
    {
        $title = "Order Data";
        if (auth()->user()->role_id == Role::ADMIN_ID) {
            $orders = Order::with(['bank', 'note', 'payment', 'user', 'status', 'orderDetails.product'])
                ->where(['is_done' => 0])
                ->orderBy('id', 'ASC')
                ->get();
        } else {
            $orders = Order::with(['bank', 'note', 'payment', 'user', 'status', 'orderDetails.product'])
                ->where(['user_id' => auth()->user()->id, 'is_done' => 0])
                ->orderBy('id', 'ASC')
                ->get();
        }
        $status = Status::all();
    
        return view('order.order_data', compact('title', 'orders', 'status'));
    }
    

    public function orderDataFilter(Request $request, $status_id)
    {
        $title = "Order Data";
        $orders = Order::with(['bank', 'note', 'payment', 'user', 'status', 'orderDetails.product'])
            ->where('status_id', $status_id)
            ->orderBy('id', 'ASC')
            ->get();
        $status = Status::all();
    
        return view('order.order_data', compact('title', 'orders', 'status'));
    }
    

    public function getOrderData(Order $order)
    {
        $order->load('orderDetails.product', 'user', 'note', 'status', 'bank', 'payment');
        
        // Return order with related products and their details
        return response()->json($order);
    }    
    
    public function cancelOrder(Order $order)
    {
        if ($order->status_id == 5) {
            $message = "Your order is already canceled!";
    
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        $updated_data = [
            "status_id" => 5,
            "note_id" => 6,
            "refusal_reason" => null,
        ];
    
        $order->fill($updated_data);
    
        if ($order->isDirty()) {
            $order->save();
    
            // Panggil kembali fungsi couponBack
            $this->couponBack($order);
    
            $message = "Your order has been canceled!";
    
            myFlasherBuilder(message: $message, success: true);
            return redirect("/order/order_data");
        }
    }
    
    private function couponBack(Order $order)
    {
        // Kembalikan kupon pengguna jika menggunakan kupon
        $user = Auth::user();
    
        $new_coupon = (int)$user->coupon + (int)$order->coupon_used;
    
        $user->coupon = $new_coupon;
    
        if ($user->isDirty()) {
            $user->save();
        }
    }    


    public function rejectOrder(Request $request, Order $order)
{
    try {
        DB::statement('CALL reject_order_procedure(?, ?, ?)', [
            $order->id,
            $request->refusal_reason,
            Auth::id(),
        ]);

        $message = "Order rejected successfully!";
        myFlasherBuilder(message: $message, success: true);
    } catch (\Exception $e) {
        $message = $e->getMessage();
        myFlasherBuilder(message: $message, failed: true);
    }

    return redirect("/order/order_data");
}

    public function approveOrder(Order $order)
{
    // Validasi: Check jika order sudah disetujui
    if ($order->status_id == 1) {
        $message = "Order status is already approved by admin";
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }

    // Validasi: Check jika order sudah ditolak
    if ($order->status_id == 3) {
        $message = "Can't approve the order that has been rejected before";
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }

    // Validasi: Check jika order sudah dibatalkan
    if ($order->status_id == 5) {
        $message = "Can't approve the order that has been canceled by user";
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }

    // Validasi: Check jika bukti transfer tidak ada
    if ($order->transaction_doc == env("IMAGE_PROOF")) {
        $message = "No transfer proof uploaded!";
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }

    // Tentukan nilai note_id berdasarkan payment_id
    $note_id = ($order->payment_id == 1) ? 4 : 1;

    try {
        // Panggil stored procedure untuk memperbarui status order
        DB::select('CALL approve_order_procedure(?, ?)', [
            $order->id,
            $note_id
        ]);

        $message = "Order approved successfully!";
        myFlasherBuilder(message: $message, success: true);
        return redirect("/order/order_data");
    } catch (\Exception $exception) {
        $message = $exception->getMessage();
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }
}
    
public function endOrder(Order $order)
{
    // Validasi: Cek jika order sudah selesai
    if ($order->status->order_status == "done") {
        $message = "The order has already succeeded by admin!";
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }

    // Validasi: Cek jika order belum disetujui
    if ($order->status->order_status != "approve") {
        $message = "Order has not been approved by the admin!";
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }

    // Tambahkan poin untuk user
    $user = $order->user;
    foreach ($order->orderDetails as $orderDetail) {
        $product = $orderDetail->product;
        
        // Aturan poin berdasarkan kategori
        $category_points = [
            "new_laptop" => 12,
            "second_laptop" => 9,
            "others" => 3,
        ];

        // Periksa kategori produk dan ambil poin yang sesuai
        $points = $category_points[$product->category] ?? 2; // Default poin adalah 2 jika kategori tidak ditemukan
        $user->point += $points * $orderDetail->quantity;
    }
    $user->save();

    // Panggil stored procedure untuk update status dan tambah transaksi
    try {
        DB::select('CALL end_order_procedure(?, ?)', [
            $order->id,
            $order->total_price,
        ]);

        $message = "Order has been ended by admin";
        myFlasherBuilder(message: $message, success: true);
        return redirect("/order/order_history");
    } catch (\Exception $exception) {
        $message = $exception->getMessage();
        myFlasherBuilder(message: $message, failed: true);
        return redirect("/order/order_data");
    }
}

    public function orderHistory()
    {
        $title = "History Data";
    
        // Cek jika role user adalah admin atau owner
        if (auth()->user()->role_id == Role::ADMIN_ID || auth()->user()->role_id == Role::OWNER_ID) {
            // Admin dan Owner dapat melihat semua order yang sudah selesai
            $orders = Order::with("bank", "note", "payment", "user", "status", "product")
                           ->where(["is_done" => 1])
                           ->orderBy("id", "ASC")
                           ->get();
        } else {
            // Jika bukan admin atau owner, hanya dapat melihat pesanan mereka sendiri
            $orders = Order::with("bank", "note", "payment", "user", "status", "product")
                           ->where(["user_id" => auth()->user()->id, "is_done" => 1])
                           ->orderBy("id", "ASC")
                           ->get();
        }
    
        $status = Status::all();
    
        return view("/order/order_data", compact("title", "orders", "status"));
    }
    


    public function getProofOrder(Order $order)
    {
        $order->load("status");
        return  $order;
    }


    public function uploadProof(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'old_image_proof' => 'required',
            'image_upload_proof' => 'required|image|file|max:2048',
        ]);

        if ($validator->fails()) {
            $message = "Failed when upload an image";
            myFlasherBuilder(message: $message, failed: true);

            return redirect("/order/order_data");
        }

        if ($request->file("image_upload_proof")) {
            if ($validator->validated()["old_image_proof"] != env("IMAGE_PROOF")) {
                Storage::delete($validator->validated()["old_image_proof"]);
            }

            $new_image = $request->file("image_upload_proof")->store("proof");
        }

        $order->transaction_doc = $new_image;
        $order->note_id = 3;
        $order->save();

        $message = "Proof transfer uploaded successfully";
        myFlasherBuilder(message: $message, success: true);

        return redirect("/order/order_data");
    }


    public function editOrderGet(Order $order)
    {
        if ($order->status_id == 5) {
            $message = "Action failed, order is already canceled by the user";
            myFlasherBuilder(message: $message, failed: true);

            return redirect("/order/order_data/");
        }

        $title = "Edit Order";
        $order->load("product", "user", "note", "status", "bank", "payment");

        return view("/order/edit_order", compact("title", "order"));
    }

    public function editOrderPost(Request $request, Order $order)
    {
        $rules = [
            'address' => 'required|max:255',
            'province' => 'required|numeric|gt:0',
            'city' => 'required|numeric|gt:0',
            'total_price' => 'required|gt:0',
            'shipping_address' => 'required',
            'coupon_used' => 'required|gte:0'
        ];
    
        $messages = [
            'province.gt' => 'Please select the province',
            'city.gt' => 'Please select the city',
        ];
    
        if ($request->file("image_proof_edit")) {
            $rules["image_proof_edit"] = "image|file|max:2048";
        }
    
        // Validasi data umum
        $validatedData = $request->validate($rules, $messages);
    
        // Ambil semua order_details yang terkait dengan order ini
        $orderDetails = $order->orderDetails; // Pastikan relasi ini ada di model Order
        foreach ($orderDetails as $detail) {
            $product = $detail->product; // Pastikan relasi ini ada di model OrderDetail
            if (!$product) {
                return back()->withErrors(['product' => 'Product not found for order detail ID ' . $detail->id]);
            }
    
            // Validasi stok untuk masing-masing produk
            $newQuantity = $request->input('quantity_' . $detail->id); // Pastikan input sesuai
            if ($newQuantity > $product->stock) {
                return back()->withErrors([
                    'quantity_' . $detail->id => 'Sorry, available stock for ' . $product->name . ' is ' . $product->stock,
                ]);
            }
        }
    
        // Jika validasi berhasil, perbarui stok dan data order_details
        foreach ($orderDetails as $detail) {
            $product = $detail->product; // Ambil produk terkait
        
            if (!$product) {
                return back()->withErrors(['product' => 'Product not found for order detail ID ' . $detail->id]);
            }
        
            // Ambil quantity baru dari request
            $newQuantity = $request->input('quantity_' . $detail->id);
        
            // Hapus detail jika quantity null atau 0
            if ($newQuantity === null || $newQuantity == 0) {
                $detail->delete(); // Hapus dari tabel order_details
                continue; // Lanjutkan ke detail berikutnya
            }
        
            // Validasi stok
            if ($newQuantity > $product->stock) {
                return back()->withErrors([
                    'quantity_' . $detail->id => 'Sorry, available stock for ' . $product->name . ' is ' . $product->stock,
                ]);
            }
        
            // Hitung perubahan stok dan perbarui data
            $difference = $newQuantity - $detail->quantity;
            $product->stock -= $difference;
            $product->save();
        
            // Perbarui detail pesanan
            $detail->quantity = $newQuantity;
            $detail->save();
        }
        
    
        // Perbarui data di tabel orders
        if ($request->file("image_proof_edit")) {
            if ($order->transaction_doc != env("IMAGE_PROOF")) {
                Storage::delete($order->transaction_doc);
            }
    
            $validatedData["transaction_doc"] = $request->file("image_proof_edit")->store("proof");
        }
    
        $order->fill($validatedData);
    
        if ($order->isDirty()) {
            $order->save();
    
            $message = "Order has been updated!";
            myFlasherBuilder(message: $message, success: true);
    
            return redirect("/order/order_data");
        } else {
            $message = "Action failed, no changes detected";
            myFlasherBuilder(message: $message, failed: true);
    
            return redirect("/order/edit_order/" . $order->id);
        }
    }
    


    public function deleteProof(Order $order)
    {
        if ($order->transaction_doc != env("IMAGE_PROOF")) {
            Storage::delete($order->transaction_doc);
        }

        $order->transaction_doc = env("IMAGE_PROOF");

        $order->save();

        $message = "Transfer proof removed successfully!";
        myFlasherBuilder(message: $message, success: true);

        return redirect("/order/edit_order/" . $order->id);
    }

    // Method untuk mendownload invoice
    public function downloadInvoice($orderId)
    {
        // Ambil data order dengan relasi yang diperlukan
        $order = Order::with(['orderDetails.product', 'user', 'payment', 'status'])->findOrFail($orderId);

        // Data untuk view invoice
        $data = compact('order');

        // Load view untuk invoice
        $pdfContent = view('partials.order.invoice', $data)->render();

        try {
            $pdf = new Mpdf();
            $pdf->WriteHTML($pdfContent);
            return $pdf->Output('invoice-' . $order->id . '.pdf', 'D');
        } catch (\Mpdf\MpdfException $e) {
            // Jika terjadi error, log pesan error dan tampilkan pesan yang sesuai
            \Log::error('mPDF Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }
}

