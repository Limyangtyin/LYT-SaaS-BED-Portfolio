<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Position;
use App\Policies\PositionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Position::class => PositionPolicy::class,
    ];

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
        //
    }
}
