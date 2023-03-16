<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
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
    return view('home');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('author/datatable', [AuthorController::class, 'datatable'])->name('author.datatable');
    Route::get('authorbook/datatable/{id}', [AuthorController::class, 'authorbook_datatable'])->name('authorbook.datatable');
    Route::post('author/addbook', [AuthorController::class, 'addbook'])->name('author.addbook');
    Route::resource('author', AuthorController::class, ['names' => 'author']);
    Route::get('book/datatable', [BookController::class, 'datatable'])->name('book.datatable');
    Route::get('bookauthor/datatable/{id}', [BookController::class, 'bookauthor_datatable'])->name('bookauthor.datatable');
    Route::resource('book', BookController::class, ['names' => 'book']);
});