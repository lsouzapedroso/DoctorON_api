<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicController;
use App\Http\Middleware\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'laravel-version' => app()->version(),
    ];
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::middleware([Middleware::class])->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/medicos', [MedicController::class, 'store']);
    Route::put('/medicos/{id}', [MedicController::class, 'update']);

});
