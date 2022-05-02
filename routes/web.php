<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\TestController;
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

Route::get('/chat', [ChatController::class, 'index'])->name('chat')->middleware(['auth']);
Route::post('/chat/search', [ChatController::class, 'search'])->name('chat.search')->middleware(['auth']);
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send')->middleware(['auth']);
Route::get('/chat/fetch', [ChatController::class, 'get'])->name('chat.get');//middleware(['auth']);


Route::get('/testchat', [TestController::class, 'index'])->name('testchat')->middleware(['auth']);
Route::get('/testchat/all', [ChatController::class, 'getContacts'])->middleware(['auth']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
