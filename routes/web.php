<?php

use App\Http\Controllers\StudentCardController;
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

Route::get('/student-card', [StudentCardController::class, 'index']);
Route::post('/student-card/generate', [StudentCardController::class, 'generatePDF'])->name('student-card.generate');
