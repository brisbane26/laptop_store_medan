<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'address', 'shipping_address', 'total_price', 
        'payment_id', 'note_id', 'status_id', 'transaction_doc', 
        'is_done', 'coupon_used', 'bank_id'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    
    public function admin()
    {
        return User::where('role_id', 1)->first();
    }
}
