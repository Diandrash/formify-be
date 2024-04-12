<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/v1/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

Route::get('/v1/forms', [FormController::class, 'index'])->middleware('auth:sanctum')->name('getForms');
Route::get('/v1/forms/{form:slug}', [FormController::class, 'show'])->middleware('auth:sanctum')->name('detailForm');
Route::post('/v1/forms', [FormController::class, 'store'])->middleware('auth:sanctum')->name('createForm');


Route::post('/v1/forms/{form:slug}/questions', [QuestionController::class, 'store'])->middleware('auth:sanctum')->name('createQuestion');
Route::delete('/v1/forms/{form:slug}/questions/{question}', [QuestionController::class, 'destroy'])->middleware('auth:sanctum')->name('deleteQuestion');


Route::post('/v1/forms/{form:slug}/responses', [ResponseController::class, 'store'])->middleware('auth:sanctum');
Route::get('/v1/forms/{form:slug}/responses', [ResponseController::class, 'index'])->middleware('auth:sanctum');