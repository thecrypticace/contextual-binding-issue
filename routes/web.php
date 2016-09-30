<?php

use App\SomeClass;
use Monolog\Logger;
use App\SomeClassLogger;
use Monolog\Handler\StreamHandler;
use Illuminate\Container\Container;
use Psr\Log\LoggerInterface;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/actual/alias', function () {
    $app = new Container;
    $app->instance("log", new Logger("test"));
    $app->alias("log", \Psr\Log\LoggerInterface::class);

    $app->when(SomeClass::class)
        ->needs(\Psr\Log\LoggerInterface::class)
        ->give(SomeClassLogger::class);

    $someClass = $app->make(SomeClass::class);

    $message = "";
    if ($someClass->logger instanceof SomeClassLogger) {
        $message = "Bug is fixed: ";
    } else {
        $message = "Bug is not fixed: ";
    }

    return $message . "`App\SomeClass` is an instance of: `" . get_class($someClass->logger) . "`";
});

// Container.php:633
// Changing to: if (isset($this->instances[$abstract]) && is_null($this->getContextualConcrete($abstract))) {
// resolved this bug
Route::get('/actual/instance', function () {
    $app = new Container();
    $app->instance(\Psr\Log\LoggerInterface::class, new Logger("test"));

    $app->when(SomeClass::class)
        ->needs(\Psr\Log\LoggerInterface::class)
        ->give(SomeClassLogger::class);

    $someClass = $app->make(SomeClass::class);

    $message = "";
    if ($someClass->logger instanceof SomeClassLogger) {
        $message = "Bug is fixed: ";
    } else {
        $message = "Bug is not fixed: ";
    }

    return $message . "`App\SomeClass` is an instance of: `" . get_class($someClass->logger) . "`";
});

Route::get('/expected', function () {
    $app = new Container();
    $app->when(SomeClass::class)
        ->needs(\Psr\Log\LoggerInterface::class)
        ->give(SomeClassLogger::class);

    $someClass = $app->make(SomeClass::class);

    $message = "";
    if ($someClass->logger instanceof SomeClassLogger) {
        $message = "Bug is fixed: ";
    } else {
        $message = "Bug is not fixed: ";
    }

    return $message . "`App\SomeClass` is an instance of: `" . get_class($someClass->logger) . "`";
});

Route::get('/no-context', function () {
    $app = new Container();
    $app->instance("log", app("log"));
    $app->alias("log", LoggerInterface::class);

    $app->when('App\Test1')
        ->needs('Psr\Log\LoggerInterface')
        ->give(function () {
            $log = new Logger('test1');
            $log->pushHandler(new StreamHandler(storage_path('logs/test1.log'), Logger::DEBUG));

            return $log;
        });

    $app->when('App\Test2')
        ->needs('Psr\Log\LoggerInterface')
        ->give(function () {
            $log = new Logger('test2');
            $log->pushHandler(new StreamHandler(storage_path('logs/test2.log'), Logger::DEBUG));

            return $log;
        });


    $test1 = $app->make("App\Test1");
    $test2 = $app->make("App\Test2");
    $test3 = $app->make("App\Test3");

    $message = "";
    $message .= "`App\Test1->logger` is an instance of: `" . get_class($test1->logger) . "`";

    if ($test1->logger instanceof Logger) {
        $message .= " ({$test1->logger->getName()})";
    }

    $message .= "<br>";

    $message .= "`App\Test2->logger` is an instance of: `" . get_class($test2->logger) . "`";
    if ($test2->logger instanceof Logger) {
        $message .= " ({$test2->logger->getName()})";
    }

    $message .= "<br>";

    $message .= "`App\Test3->logger` is an instance of: `" . get_class($test3->logger) . "`";
    if ($test3->logger instanceof Logger) {
        $message .= " ({$test3->logger->getName()})";
    }

    $message .= "<br>";

    return $message;
});
