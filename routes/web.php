<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductosController;

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

Route::group(['prefix' => 'admin', 'as' => 'admin'], function(){
    Route::get('/',  [App\Http\Controllers\AdminController::class, 'index']);
    Route::get('/usuarios',  [App\Http\Controllers\UsersController::class, 'index']);
    Route::post('/usuarios/edit',  [App\Http\Controllers\UsersController::class, 'editarUsuarios']);
    Route::get('/productos',  [App\Http\Controllers\ProductosController::class, 'index']);
    Route::post('/productos/all',  [App\Http\Controllers\ProductosController::class, 'all']);

    Route::resource('usuarios', UsersController::class);
    Route::resource('productos', ProductosController::class);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
