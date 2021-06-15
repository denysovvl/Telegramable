<?php

namespace Denysovvl\Providers;


use Illuminate\Support\ServiceProvider;

class TelegramableoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
        	__DIR__.'/../config/telegramable.php' => config_path('courier.php'),
    	]);
    }
}

