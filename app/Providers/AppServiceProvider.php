<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Stripe\StripeClient;
use App\Models\Service_Areas;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(StripeClient::class,function(){
            return new StripeClient(config('stripe.secret'));
        });

        // All views has service_areas for showing categories

        $service_areas = Service_Areas::all();
        View::share('service_areas', $service_areas);
    }
}
