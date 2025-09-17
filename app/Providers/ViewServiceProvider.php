<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Country;
use App\Models\Genre;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
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
        View::composer('layouts.app', function ($view) {
            $categories = Category::orderBy('name')->get();
            $genres = Genre::orderBy('name')->get();
            $countries = Country::orderBy('name')->get();

            $view->with('categories', $categories);
            $view->with('genres', $genres);
            $view->with('countries', $countries);
        });
    }
}