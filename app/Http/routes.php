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

$router->get('/', function () use ($app) {
    return $app->version();
});
$router->group(['prefix' => 'v1'], function($router){
  $router->get('meetings/{hash}', 'MeetingsController@get');
  $router->post('meetings', 'MeetingsController@create');
  $router->post('meetings/{hash}', 'MeetingAnswersController@create');
});
