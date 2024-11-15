<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Gate untuk admin saja
        Gate::define("is_admin", function (User $user) {
            return $user->role_id === Role::ADMIN_ID;
        });
        
        // Gate untuk owner saja
        Gate::define("is_owner", function (User $user) {
            return $user->role_id === Role::OWNER_ID;
        });

    }
}
