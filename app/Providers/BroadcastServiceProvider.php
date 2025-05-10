<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Broadcast::channel('renstra-channel', function ($user) {
            return true; // Ini bisa kamu sesuaikan sesuai kebutuhan
        });
        Broadcast::channel('daily-channel', function ($user) {
            return true; // Ini bisa kamu sesuaikan sesuai kebutuhan
        });
    }
}
