<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation; // <— thêm dòng này
use App\Models\Category;
use App\Models\Country;
use Illuminate\Support\Facades\View;

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

            View::composer('layouts.*', function ($view) {
                $view->with('categories', Category::select('id','name','slug')->get());
                $view->with('countries',  Country::select('id','name','slug')->get());
            });
        Relation::enforceMorphMap([
            'movie'  => \App\Models\Movie::class,
            'person' => \App\Models\Person::class,
            'user'   => \App\Models\User::class,
        ]);
    }
}
