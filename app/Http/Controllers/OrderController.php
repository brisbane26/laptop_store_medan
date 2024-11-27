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
    
        // Validasi tambahan untuk bank_id jika metode pembayaran adalah transfer bank
        if ($request->payment_method == 1) {
            $rules['bank_id'] = 'required|numeric|exists:banks,id';
        }
    
        // Validasi input
        $validatedData = $request->validate($rules, $messages);
    
        // Pengecekan stok sebelum transaksi
        foreach ($validatedData['product_id'] as $index => $productId) {
            $product = Product::findOrFail($productId);
            if ($product->stock < $validatedData['quantity'][$index]) {
                return back()->withErrors(['message' => "Stock for {$product->name} is insufficient."]);
            }
        }
    
        // Transaksi database
        DB::transaction(function () use ($validatedData) {
            // Buat data order
            $order = Order::create([
                'user_id' => auth()->id(),
                'address' => $validatedData['address'],
                'shipping_address' => $validatedData['shipping_address'],
                'total_price' => $validatedData['total_price'],
                'payment_id' => $validatedData['payment_method'],
                'note_id' => ($validatedData['payment_method'] == 1) ? 2 : 1,
                'status_id' => 2, // Status awal
                'transaction_doc' => ($validatedData['payment_method'] == 1) ? env('IMAGE_PROOF') : null,
                'is_done' => 0,
                'coupon_used' => $validatedData['coupon_used'],
                'bank_id' => $validatedData['payment_method'] == 1 ? $validatedData['bank_id'] : null,
            ]);
    
            // Buat detail order untuk setiap produk
            foreach ($validatedData['product_id'] as $index => $productId) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $validatedData['quantity'][$index],
                    'price' => $validatedData['price'][$index],
                ]);
    
                // Kurangi stok produk
                Product::where('id', $productId)->decrement('stock', $validatedData['quantity'][$index]);
            }
    
            // Hapus item di cart setelah order berhasil
            Cart::where('user_id', auth()->id())->delete();
        });
    
        // Redirect ke halaman order data setelah berhasil
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

            $this->couponBack($order);

            $message = "Your order has been canceled!";

            myFlasherBuilder(message: $message, success: true);
            return redirect("/order/order_data");
        }
    }


    private function couponBack(Order $order)
    {
        // return the user's coupon if using a coupon
        $user = Auth::user();

        $new_coupon = (int)$user->coupon + (int)$order->coupon_used;

        $user->coupon = $new_coupon;

        if ($user->isDirty()) {
            $user->save();
        }
    }


    public function rejectOrder(Request $request, Order $order)
    {
        if ($request->refusal_reason == "") {
            $message = "Refusal reason cannot be empty!";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->status_id == 4) {
            $message = "Order status is already succeeded by admin";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->status_id == 5) {
            $message = "Order status is already canceled by user";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->status_id == 3) {
            $message = "Order status is already rejected";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        // Update status to rejected and return stock for all products in the order
        foreach ($order->orderDetails as $orderDetail) {
            $this->stockReturn($orderDetail->product, $orderDetail->quantity);
        }
    
        $order->update([
            "status_id" => 3,
            "refusal_reason" => $request->refusal_reason,
        ]);
    
        $this->couponBack($order);
    
        $message = "Order rejected successfully!";
        myFlasherBuilder(message: $message, success: true);
        return redirect("/order/order_data");
    }
    


    private function stockReturn(Product $product, int $quantity)
    {
        $product->stock += $quantity;
        $product->save();
    }
    


    public function approveOrder(Order $order)
    {
        if ($order->status_id == 1) {
            $message = "Order status is already approved by admin";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->status_id == 3) {
            $message = "Can't approve the order that has been rejected before";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->status_id == 5) {
            $message = "Can't approve the order that has been canceled by user";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->transaction_doc == env("IMAGE_PROOF")) {
            $message = "No transfer proof uploaded!";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        $order->update([
            "status_id" => 1,
            "refusal_reason" => null,
            "note_id" => ($order->payment_id == 1) ? 4 : 1,
        ]);
    
        $message = "Order approved successfully!";
        myFlasherBuilder(message: $message, success: true);
        return redirect("/order/order_data");
    }
    
    public function endOrder(Order $order)
    {
        if ($order->status->order_status == "done") {
            $message = "The order has already succeeded by admin!";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        if ($order->status->order_status != "approve") {
            $message = "Order has not been approved by the admin!";
            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
    
        // Change order status to done
        $order->update([
            "status_id" => 4,
            "note_id" => 5,
            "is_done" => 1,
            "refusal_reason" => null,
        ]);
    
        // Add points for the user
        $user = $order->user;
        foreach ($order->orderDetails as $orderDetail) {
            $product = $orderDetail->product;
            $point_rules = [
                "1" => 5,
                "2" => 3,
                "3" => 3,
                "4" => 3,
            ];
            $points = $point_rules[$product->id] * $orderDetail->quantity;
            $user->point += $points;
        }
        $user->save();
    
        // Add transactional data
        $transactional_data = [
            "category_id" => 1,
            "description" => "Sales of products in order #{$order->id}",
            "income" => $order->total_price,
            "outcome" => null,
        ];
        Transaction::create($transactional_data);
    
        $message = "Order has been ended by admin";
        myFlasherBuilder(message: $message, success: true);
        return redirect("/order/order_history");
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
