<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\SchoolYear;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('activeSchoolYear', SchoolYear::where('is_active', true)->first());
        });
    }
}
