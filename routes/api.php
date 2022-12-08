<?php

use App\Http\Controllers\login\usercontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [usercontroller::class, 'register']);
Route::post('/login', [usercontroller::class, 'login']);

Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::get('/userProfile', [usercontroller::class, 'userProfile']);
    Route::get('/logout', [usercontroller::class, 'logout']);
});
