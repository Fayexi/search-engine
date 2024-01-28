<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('search_engine');
});


Route::get('/getResult', [ApiController::class, 'getResult']);
Route::post('/saveBookmark', [ApiController::class, 'saveBookmark']);
Route::post('/removeBookmark', [ApiController::class, 'removeBookmark']);
