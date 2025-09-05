<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', 'App\Http\Controllers\UserController@login');
Route::post('/login', 'App\Http\Controllers\UserController@submitLogin');
Route::get('/register', 'App\Http\Controllers\UserController@register');
Route::post('/register', 'App\Http\Controllers\UserController@submitRegister');
Route::get('/logout', 'App\Http\Controllers\UserController@logout');


Route::get('/articles', 'App\Http\Controllers\ArticleController@index');
Route::get('/article/{id}', 'App\Http\Controllers\ArticleController@show');
Route::post('/articles', 'App\Http\Controllers\ArticleController@store');
Route::put('/articles/{id}', 'App\Http\Controllers\ArticleController@update');
Route::delete('/articles/{id}', 'App\Http\Controllers\ArticleController@destroy');
