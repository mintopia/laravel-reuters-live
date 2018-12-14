<?php
namespace Mintopia\ReutersLive;

use Illuminate\Support\ServiceProvider;

class ReutersLiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../resources/config/reuters-live.php' => config_path('reuters-live.php')
        ]);

        $this->app->singleton('Mintopia\ReutersLive\ReutersLive', function ($app) {
            $live = new ReutersLive;
            $live->setUri(config('reuters-live.url'));
            return $live;
        });
    }
}