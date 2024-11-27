<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts'; // Nama tabel
    protected $fillable = [
        'user_id', 'product_id', 'quantity'
    ];

    public static function getItems($userId)
    {
        return self::where('user_id', $userId)->with('product')->get();
    }

    // Relasi ke user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke produk
    public function product() {
        return $this->belongsTo(Product::class);
    }
}