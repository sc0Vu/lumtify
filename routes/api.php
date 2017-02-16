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

// auths
$app->post('/auth/login', "AuthController@login");
$app->get('/auth/logout', ["middleware" => "api", "uses" => "AuthController@logout"]);
$app->get('/auth/user', ["middleware" => "api", "uses" => "AuthController@user"]);
$app->get('/auth/refresh', ["middleware" => "api", "uses" => "AuthController@refresh"]);

// articles
// $app->get('/articles', 'ArticleController@articles');
// $app->post('/articles', 'ArticleController@create');
// $app->get('/articles/{link}', 'ArticleController@read');
// $app->put('/articles/{link}', 'ArticleController@update');
// $app->delete('/articles/{link}', 'ArticleController@delete');

// users
// $app->get('/users', 'UserController@users');
$app->post('/users', "UserController@create");
$app->get('/users/{uid}', 'UserController@read');
$app->put('/users/{uid}', 'UserController@update');
$app->delete('/users/{uid}', 'UserController@delete');