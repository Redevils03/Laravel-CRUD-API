<?php

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

// Route::get('/', 'App\Http\Controllers\bookController@index');
// Route::post('/', 'App\Http\Controllers\bookController@store');
 
// Route::resource('/', App\Http\Controllers\bookController::class);
Route::controller(App\Http\Controllers\bookController::class)->group(function(){
  Route::get('/', 'index')->name('index');
  Route::post('/', 'store')->name('store');
  Route::put('/{book}', 'update')->name('update');
  Route::delete('/{book}', 'destroy')->name('destroy');
});