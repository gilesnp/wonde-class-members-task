<?php

use App\Http\Controllers\WondeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Wonde home page
Route::get('/wonde', '\App\Http\Controllers\WondeController@index');

// School page
Route::match(['get', 'post'], '/wonde/school', '\App\Http\Controllers\WondeController@school');

// Employee page
Route::post('/wonde/school/employee', '\App\Http\Controllers\WondeController@employee');

// Classes for day page
Route::post('/wonde/school/employee/classesForDay', '\App\Http\Controllers\WondeController@classesForDay');