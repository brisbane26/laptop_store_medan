<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Roles;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'customer_name', 'laptop_model', 'problem_description', 'price', 'status','rejection_reason'];
    
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