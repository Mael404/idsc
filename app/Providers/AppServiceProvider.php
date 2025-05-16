<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\SchoolYear;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();

            $view->with('activeSchoolYear', $activeSchoolYear ?? 'No school year set yet');
        });
    }
}
