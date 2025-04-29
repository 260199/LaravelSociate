<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use App\Events\RenstraInputEvent;

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
    }
}
