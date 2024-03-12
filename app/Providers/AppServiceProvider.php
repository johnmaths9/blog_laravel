<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
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
        $settings = Setting::checkSettigs();
        $categories = Category::with('children')->where('parent',0)->orWhere('parent',null);
        $lastFivePosts = Post::with('category','user')->orderBy('id')->limit(5)->get();
        view()->share([
            'setting'=>$settings,
            'categories'=>$categories,
            'lastFivePosts'=>$lastFivePosts,
        ]);
    }
}
