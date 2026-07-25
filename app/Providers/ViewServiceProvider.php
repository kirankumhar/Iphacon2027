<?php
// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\MenuComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register the view composer for all views
        View::composer('*', MenuComposer::class);
    }
}
