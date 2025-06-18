<?php

namespace App\Providers;

use App\Models\Inbox;
use Illuminate\Support\ServiceProvider;
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
        View::composer('layouts.main', function ($view) {
            $user = auth()->user();
            $inbox = Inbox::where('receiver_id', auth()->id())->where('is_read', false)->get()->count();
            $view->with(compact('user', 'inbox'));
        });
    }
}
