<?php

/*
|--------------------------------------------------------------------------
| Application API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('test', function ()    {
    return "hello api";
});

$app->get('auth', ["middleware" => "api"], function () {
    return "hello api";
});

$app->post('/users/register', ["middleware" => "guest"], "AuthController@register");

$app->post('/users/login', ["middleware" => "guest"], function () {
    return "hello api";
});

$app->post('/users/logout', ["middleware" => "guest"], function () {
    return "hello api";
});