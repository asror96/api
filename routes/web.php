<?php

/* @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router){
    return $router->app->version();
});

$router->group(['prefix'=>'User'],function()use ($router)
{
    $router->post('register','UsersController@register');
    $router->post('login','UsersController@login');
});
$router->group(['prefix'=>'/user'],function()use ($router)
{
    $router->post('/register','UsersController@register');
    $router->post('login','UsersController@login');
});
//,'middleware'=>'auth'
$router -> group(['prefix'=>'user','middleware'=>'auth'],function () use ($router){
    $router -> post('refreshAccessToken','UsersController@refreshToken');

    $router->get('get-items','UsersController@getItems');
    $router->get('','UsersController@getItems');

    $router->get('get-item/{id}','UsersController@getItem');
    $router->get('{id}','UsersController@getItem');

    $router->put('update/{id}','UsersController@update');
    $router->put('{id}','UsersController@update');

    $router->delete('delete/{id}','UsersController@delete');
    $router->delete('{id}','UsersController@delete');
});
$router -> group(['prefix'=>'task','middleware'=>'auth'],function () use ($router){
    $router -> post('', 'TaskController@create');
    $router -> post('create', 'TaskController@create');

    $router->get('get-items','TaskController@getItems');
    $router->get('','TaskController@getItems');

    $router->get('get-item/{id}','TaskController@getItem');
    $router->get('{id}','TaskController@getItem');

    $router->put('get-item/{id}','TaskController@update');
    $router->put('{id}','TaskController@update');

    $router->delete('delete/{id}','TaskController@delete');
    $router->delete('{id}','TaskController@delete');
});
$router -> group(['prefix'=>'list','middleware'=>'auth'],function () use ($router){
    $router -> post('', 'ListController@create');
    $router -> post('create', 'ListController@create');

    $router->get('get-items','ListController@getItems');
    $router->get('','ListController@getItems');

    $router->get('get-item/{id}','ListController@getItem');
    $router->get('{id}','ListController@getItem');

    $router->put('get-item/{id}','ListController@getItem');
    $router->put('{id}','ListController@getItem');

    $router->delete('delete/{id}','ListController@delete');
    $router->delete('{id}','ListController@delete');
});
