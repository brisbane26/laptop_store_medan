<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Roles;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'laptop_model', 
        'equipments', 
        'problem_description', 
        'laptop_image', 
        'order_date', 
        'status', 
        'price', 
        'end_date'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    
        
    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
{
    return $this->belongsTo(Role::class);
}

}