<?php

use App\Http\Controllers\VideoController;
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
    

Route::get('video', [VideoController::class, 'index']);

Route::post('video', [VideoController::class, 'store']);
// Route::post('video', [VideoController::class, 'index']);
// Route::resource('video', 'VideoController')