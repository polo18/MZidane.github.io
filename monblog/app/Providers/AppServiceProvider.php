<?php

namespace App\Providers;

use Illuminate\Support\Facades\{View, Blade, Route};
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\HomeComposer;

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
        setlocale(LC_TIME, config('app.locale'));
        
        View::composer(['front.layout', 'front.index'], HomeComposer::class);

        Blade::if('request', function ($url) {
            return request()->is($url);
        });

        View::composer('back.layout', function ($view) {
            $title = config('titles.' . Route::currentRouteName());
            $view->with(compact('title'));
        });
    }
}
