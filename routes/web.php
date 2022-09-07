<?php

// $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
// $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
// $response->header('Access-Control-Allow-Origin', '*');
$router->get('/', function () use ($router) {
  return "Welcome";
});

$router->group(['middleware' => ['auth', 'verified']], function () use ($router) {
  $router->post('/api/logout', 'AuthController@logout');
  $router->post('/api/refresh', 'AuthController@refresh');
  $router->post('/api/me', 'AuthController@me');
  $router->get('/api/sendmail', 'Mailcontroller@mail');
  $router->post('/password/change', ['as' => 'password.change', 'uses' => 'ChangePasswordController@changepassword']);
});
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
  $router->get('users',  ['uses' => 'UserController@showAllUsers']);
});
$router->group(['middleware' => 'auth'], function () use ($router) {
  $router->post('/tasks/create', 'TaskController@createTask');
  $router->get('/tasks', 'TaskController@showAllTasks');
  $router->get('/utasks/{id}', 'TaskController@showUserTasks');
  $router->post('/tasks/{id}', 'TaskController@updateTask');
});
$router->group(['prefix' => 'api'], function () use ($router) {
  $router->post('users/login', 'AuthController@login');
  $router->get('users/{id}', ['uses' => 'UserController@showOneUser']);
  $router->post('users/signup', ['uses' => 'UserController@create']);
  $router->delete('users/{id}', ['uses' => 'UserController@delete']);
  $router->put('users/{id}', ['uses' => 'UserController@update']);
});
$router->post('/email/request-verification', ['as' => 'email.request.verification', 'uses' => 'AuthController@emailRequestVerification']);
$router->post('/password/reset-request', 'RequestPasswordController@sendResetLinkEmail');
$router->post('/email/verify', ['as' => 'email.verify', 'uses' => 'AuthController@emailVerify']);
$router->post('/password/reset', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@reset']);
// $router->get('/tasks','TaskController@showAllTasks');
// $router->post('/tasks/create','TaskController@createTask');
