<?php

use App\SomeClass;
use App\SomeClassLogger;
use Illuminate\Container\Container;

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

Route::get('/actual/app', function () {
    $app = app();
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
Route::get('/actual/container', function () {
    $app = new Container();

    $app->when(SomeClass::class)
        ->needs(\Psr\Log\LoggerInterface::class)
        ->give(SomeClassLogger::class);

    $app->instance(\Psr\Log\LoggerInterface::class, app("log"));

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
