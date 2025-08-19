<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class NavigationServiceProvider extends ServiceProvider
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
        View::share('navigationLinks', [
            ['route' => 'home', 'icon' => 'fas fa-home', 'text' => 'Home'],
            ['route' => 'dashboard', 'icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard'],
            ['route' => 'permissions', 'icon' => 'fas fa-users', 'text' => 'Permissions'],
            ['route' => 'products.index', 'icon' => 'fas fa-box', 'text' => 'Products'],
            ['text' => 'Tools', 'icon' => 'fas fa-tools', 'children' => [
                ['route' => 'tools.even-numbers', 'icon' => 'fas fa-calculator', 'text' => 'Even Numbers'],
                ['route' => 'tools.multiplication', 'icon' => 'fas fa-times', 'text' => 'Multiplication'],
            ]],
            ['route' => 'catalog', 'icon' => 'fas fa-book', 'text' => 'Catalog'],
        ]);
    }
}
