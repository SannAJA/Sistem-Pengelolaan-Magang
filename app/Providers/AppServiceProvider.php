<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan binding atau layanan tambahan ke container aplikasi.
     */
    public function register(): void
    {
        //
    }

    /**
     * Menjalankan konfigurasi bootstrapping saat aplikasi siap digunakan.
     */
    public function boot(): void
    {
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
