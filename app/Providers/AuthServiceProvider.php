<?php

namespace App\Providers;

use App\Models\Company;
use App\Policies\CompanyPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Position;
use App\Policies\PositionPolicy;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Position::class => PositionPolicy::class,
        Company::class => CompanyPolicy::class,
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
        $this->registerPolicies();

        Gate::define('browse', [CompanyPolicy::class, 'browse']);
        Gate::define('read', [CompanyPolicy::class, 'read']);
        Gate::define('update', [CompanyPolicy::class, 'update']);
        Gate::define('create', [CompanyPolicy::class, 'create']);
        Gate::define('delete', [CompanyPolicy::class, 'delete']);
        Gate::define('search', [CompanyPolicy::class, 'search']);
        Gate::define('restore', [CompanyPolicy::class, 'restore']);
        Gate::define('restoreAll', [CompanyPolicy::class, 'restoreAll']);
        Gate::define('trash', [CompanyPolicy::class, 'trash']);
        Gate::define('trashAll', [CompanyPolicy::class, 'trashAll']);

        Gate::define('browse', [PositionPolicy::class, 'browse']);
        Gate::define('read', [PositionPolicy::class, 'read']);
        Gate::define('update', [PositionPolicy::class, 'update']);
        Gate::define('create', [PositionPolicy::class, 'create']);
        Gate::define('delete', [PositionPolicy::class, 'delete']);
        Gate::define('search', [PositionPolicy::class, 'search']);
        Gate::define('restore', [PositionPolicy::class, 'restore']);
        Gate::define('restoreAll', [PositionPolicy::class, 'restoreAll']);
        Gate::define('trash', [PositionPolicy::class, 'trash']);
        Gate::define('trashAll', [PositionPolicy::class, 'trashAll']);

        Gate::define('browse', [UserPolicy::class, 'browse']);
        Gate::define('read', [UserPolicy::class, 'read']);
        Gate::define('update', [UserPolicy::class, 'update']);
        Gate::define('create', [UserPolicy::class, 'create']);
        Gate::define('delete', [UserPolicy::class, 'delete']);
        Gate::define('search', [UserPolicy::class, 'search']);
        Gate::define('restore', [UserPolicy::class, 'restore']);
        Gate::define('restoreAll', [UserPolicy::class, 'restoreAll']);
        Gate::define('trash', [UserPolicy::class, 'trash']);
        Gate::define('trashAll', [UserPolicy::class, 'trashAll']);
    }
}
