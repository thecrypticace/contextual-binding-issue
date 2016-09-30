<?php

namespace App\Providers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when('App\Test1')
            ->needs('Psr\Log\LoggerInterface')
            ->give(function () {
                $log = new Logger('test1');
                $log->pushHandler(new StreamHandler(storage_path('logs/test1.log'), Logger::DEBUG));

                return $log;
            });

        $this->app->when('App\Test2')
            ->needs('Psr\Log\LoggerInterface')
            ->give(function () {
                $log = new Logger('test2');
                $log->pushHandler(new StreamHandler(storage_path('logs/test2.log'), Logger::DEBUG));

                return $log;
            });
    }
}
