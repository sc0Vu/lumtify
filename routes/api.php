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


// auths
$app->post('/auth/login', "AuthController@login");

$app->post('/auth/logout', ["middleware" => "api"], "AuthController@logout");

// articles
// $app->get('/articles', 'ArticleController@articles');
// $app->post('/articles', 'ArticleController@update');
// $app->get('/articles/{link}', 'ArticleController@read')->where(['link' => '/^[a-zA-Z0-9]*$/']);
// $app->put('/articles/{link}', 'ArticleController@update')->where(['link' => '/^[a-zA-Z0-9]*$/']);
// $app->delete('/articles/{link}', 'ArticleController@delete')->where(['link' => '/^[a-zA-Z0-9]*$/']);
// users
// $app->get('/users', 'UserController@users');
// $app->get('/users{uid}', 'UserController@read')->where(['uid' => '/^[a-zA-Z0-9]{32}$/']);
// $app->put('/users{uid}', 'UserController@update')->where(['uid' => '/^[a-zA-Z0-9]{32}$/']);
$app->post('/users', "UserController@create");
// $app->delete('/users{uid}', 'UserController@delete')->where(['uid' => '/^[a-zA-Z0-9]{32}$/']);