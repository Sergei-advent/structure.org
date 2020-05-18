<?php

namespace App\Providers;

use App\Services\CompanyStructureService;
use App\Services\ExternalRequestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ExternalRequestService', function($app) {
            return new ExternalRequestService();
        });

        $this->app->singleton('CompanyStructureService', function($app) {
            return new CompanyStructureService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
