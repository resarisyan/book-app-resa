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
Route::get('admin/books', [App\Http\Controllers\AdminController::class, 'books'])->name('admin.books')->middleware('is_admin');
Route::post('amin/books', [App\Http\Controllers\AdminController::class, 'submit_book'])->name('admin.book.submit')->middleware('is_admin');
Route::patch('admin/books/update', [App\Http\Controllers\AdminController::class, 'update_book'])->name('admin.book.update')->middleware('is_admin');
Route::get('admin/ajaxadmin/dataBuku/{id}', [App\Http\Controllers\AdminController::class, 'getDataBuku']);
Route::post('admin/books/delete/{id}', [App\Http\Controllers\AdminController::class, 'delete_book'])->name('admin.book.delete')->middleware('is_admin');
Route::get('admin/print_books', [App\Http\Controllers\AdminController::class, 'print_books'])->name('admin.print.books')->middleware('is_admin');
