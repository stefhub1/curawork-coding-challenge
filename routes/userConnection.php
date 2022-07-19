<?php

use App\Http\Controllers\RequestUserController;
use Illuminate\Support\Facades\Route;

Route::resource('/request-users', RequestUserController::class)
	->names(['index' => 'request-users'])
	->only(['index', 'store', 'update', 'destroy']);
Route::get('/request-users/{tab?}', [RequestUserController::class, 'index']);