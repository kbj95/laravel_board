<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardsController;

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


Route::resource('/boards',BoardsController::class);
// GET|HEAD        boards .......................... boards.index › BoardsController@index  
// POST            boards .......................... boards.store › BoardsController@store  
// GET|HEAD        boards/create ................... boards.create › BoardsController@create  
// GET|HEAD        boards/{board} .................. boards.show › BoardsController@show  
// PUT|PATCH       boards/{board} .................. boards.update › BoardsController@update  
// DELETE          boards/{board} .................. boards.destroy › BoardsController@destroy  
// GET|HEAD        boards/{board}/edit ............. boards.edit › BoardsController@edit  
