<?php

use App\Http\Controllers\ActionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\PostController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[Home::class,'index']);
Route::get('homepage',[Home::class,'index']);

// dashboard routes
Route::get('login',[Home::class,'login']);
Route::post('login-post',[Home::class,'loginAction'])->name('login.post');
Route::get('register',[Home::class,'register']);
Route::post('register-post',[Home::class,'registerAction'])->name('register.post');
Route::get('logout',[Home::class,'logout']);



// Post Routes
Route::post('new-post',[PostController::class,'store'])->name('new.post');


// Action Routes
Route::get('comment',[ActionController::class,'index']);
Route::post('add-comment',[ActionController::class,'store']);