<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/verify', [UserController::class, 'verify']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/upload', [PropertyController::class, 'upload'])->middleware(['auth:sanctum','role:agent']);
