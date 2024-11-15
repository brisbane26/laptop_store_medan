<?php

namespace App\Http\Controllers;

use App\Models\{Role, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Middleware\Authorize;

class HomeController extends Controller
{
    public function index()
    {
        $title = "Home";

        return view("/home/index", compact("title"));
    }

    public function customers()
{
    // Memeriksa apakah pengguna memiliki role_id admin (1) atau owner (3)
    if (!in_array(auth()->user()->role_id, [Role::ADMIN_ID, Role::OWNER_ID])) {
        abort(403, 'This action is unauthorized.');
    }

    $title = "Customers";
    $customers = User::with("role")->get();

    return view("home/customers", compact("title", "customers"));
}

}