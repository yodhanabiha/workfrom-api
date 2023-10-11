<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');

    $router->group(['prefix' => 'register'], function () use ($router) {
        $router->post('user', 'AuthController@registerUser');
        $router->post('admin', 'AuthController@registerAdmin');
        $router->post('mitra', 'AuthController@registerMitra');
    });
    
});

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->group(['prefix' => 'user', 'middleware' => 'permission:user'], function () use ($router) {
        $router->post('review/{id}', 'RoomDataController@postReview');
        $router->post('booking', 'RoomDataController@createBooking');
    });

    $router->group(['prefix' => 'mitra', 'middleware' => 'permission:mitra'], function () use ($router) {
        $router->get('index', 'MitraController@index');
        $router->post('create', 'MitraController@create');
        $router->put('update/{id}', 'MitraController@update');
        $router->delete('delete/{id}', 'MitraController@destroy');
    });

    $router->group(['prefix' => 'admin', 'middleware' => 'permission:admin'], function () use ($router) {
        $router->get('rooms', 'AdminController@rooms');
        $router->get('document/{id}', 'AdminController@checkDocument');
        $router->put('approve/{id}', 'AdminController@approveRoom');
     
    });

});

$router->group(['prefix' => 'rooms'], function () use ($router) {

    $router->get('/', 'RoomDataController@getRooms');
    $router->get('/{id}', 'RoomDataController@getRoomById');
    $router->get('/images/{id}', 'RoomDataController@getImages');
    $router->get('/image/{id}', 'RoomDataController@getImage');
    $router->get('/review/{id}', 'RoomDataController@sumReview');
    $router->get('/types/{id}', 'RoomDataController@getTypes');
    $router->get('/facilities/{id}', 'RoomDataController@getFacilities');

    $router->post('/distance/{id}', 'RoomDataController@getDistance');
    $router->post('/booking/guest', 'RoomDataController@createBookingGuest');

});


