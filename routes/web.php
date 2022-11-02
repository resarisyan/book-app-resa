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

// Auth::user(); = menampilkan data yang sudah login
// Auth::check(); = mengecek user sudah login

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', function(){
//     // return $user = Auth::user();
// });

Route::get('/test', [App\Http\Controllers\TestController::class, 'index'])->name('test')->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/home', function() {
//     return view('home');
// })->name('home')->middleware('auth');

Route::get('admin/home', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.home')->middleware('is_admin');
