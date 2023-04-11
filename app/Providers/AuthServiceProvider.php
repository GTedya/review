<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Geo;
use App\Models\News;
use App\Models\Order;
use App\Models\Page;
use App\Models\VehicleType;
use App\Policies\GeoPolicy;
use App\Policies\NewsPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PagePolicy;
use App\Policies\VehicleTypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Geo::class => GeoPolicy::class,
        News::class => NewsPolicy::class,
        Page::class => PagePolicy::class,
        VehicleType::class => VehicleTypePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
