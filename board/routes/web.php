<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\userController;

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

// Board
Route::resource('/boards',BoardsController::class);
// GET|HEAD        boards .......................... boards.index › BoardsController@index  
// POST            boards .......................... boards.store › BoardsController@store  
// GET|HEAD        boards/create ................... boards.create › BoardsController@create  
// GET|HEAD        boards/{board} .................. boards.show › BoardsController@show  
// PUT|PATCH       boards/{board} .................. boards.update › BoardsController@update  
// DELETE          boards/{board} .................. boards.destroy › BoardsController@destroy  
// GET|HEAD        boards/{board}/edit ............. boards.edit › BoardsController@edit 

// Users
Route::get('/users/login',[UserController::class, 'login'])->name('users.login');
Route::post('/users/loginpost',[UserController::class, 'loginpost'])->name('users.login.post');
Route::get('/users/registration',[UserController::class, 'registration'])->name('users.registration');
Route::post('/users/registrationpost',[UserController::class, 'registrationpost'])->name('users.registration.post');
Route::get('/users/logout',[UserController::class, 'logout'])->name('users.logout');
Route::get('/users/withdraw',[UserController::class, 'withdraw'])->name('users.withdraw');
Route::get('/users/edit',[UserController::class, 'edit'])->name('users.edit');
Route::post('/users/editpost',[UserController::class, 'editpost'])->name('users.editpost');
