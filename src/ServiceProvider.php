<?php

namespace Pagocards\SDK;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pagocards.php',
            'pagocards'
        );

        $this->app->singleton('pagocards', function ($app) {
            $config = $app['config']['pagocards'];

            return new Client(
                $config['public_key'] ?? env('PAGOCARDS_PUBLIC_KEY'),
                $config['secret_key'] ?? env('PAGOCARDS_SECRET_KEY'),
                $config['base_url'] ?? env('PAGOCARDS_API_URL', 'https://pagocards.com')
            );
        });
    }

    /**
     * Boot services
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/pagocards.php' => config_path('pagocards.php'),
        ], 'config');
    }

    /**
     * Get provided services
     *
     * @return array
     */
    public function provides()
    {
        return ['pagocards'];
    }
}

