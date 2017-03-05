<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get("/", "HomeController@index");

$app->get("/about", "HomeController@index");

$app->get("/articles/{link}/read", "HomeController@index");

$app->get("/articles/{link}/update", "HomeController@index");

$app->get("/articles/create", "HomeController@index");

$app->get("/articles", "HomeController@index");

$app->get("/login", "HomeController@index");

$app->get("/register", "HomeController@index");

$app->get("/users/{uid}/profile", "HomeController@index");

$app->get("/users/{uid}/setting", "HomeController@index");

$app->get("/users", "HomeController@index");