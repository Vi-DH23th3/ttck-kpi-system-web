<?php

namespace App\Providers;

//use Illuminate\Support\ServiceProvider;

use App\Models\PhanCongCongViec;
use App\Models\User;
use App\Policies\PhanCongCVPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider
{
    //  protected $policies = [
    //     User::class => UserPolicy::class,
    //     PhanCongCongViec::class => PhanCongCVPolicy::class
    //  ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('nav', function ($user) {
            return in_array($user->role,['admin', 'manager']);
        });
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
        Gate::define('manager', function ($user) {
            return $user->role === 'manager';
        });
        Gate::define('nhanvien', function ($user) {
            return $user->role === 'staff';
        });
    }
}
