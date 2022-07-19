<?php

use App\Http\Controllers\CommonConnectionController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\RequestUserController;
use Illuminate\Support\Facades\Route;

Route::resource('/request-users', RequestUserController::class)
	->names(['index' => 'request-users'])
	->only(['index', 'store', 'update', 'destroy']);
Route::get('/request-users/{tab?}', [RequestUserController::class, 'index']);

Route::resource('/connections', ConnectionController::class)
	->names(['index' => 'connections'])
	->only(['index', 'destroy']);

Route::resource('/common-connections', CommonConnectionController::class)
	->names(['index' => 'common-connections'])
	->only(['index']);