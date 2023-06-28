<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertiesController;
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

//public
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('properties', [PropertiesController::class, 'index']);
Route::get('/property', [PropertiesController::class, 'show']);
Route::get('favorites', [PropertiesController::class, 'favorites']);
Route::post('changefavorite', [PropertiesController::class, 'change_favorite_state']);


 //private
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);
  

});

Route::get('sendSMS' , [AuthController::class , 'index']);