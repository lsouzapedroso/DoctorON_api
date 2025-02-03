<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\MedicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::get('/healthz', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/info', function () {
    return response()->json([
        'version' => config('app.version'),
        'url' => config('app.url'),
        'env' => config('app.env'),
    ]);
});

Route::get('/cidades', [CityController::class, 'index']);
Route::get('/cidades/{id_cidade}/medicos', [CityController::class, 'getMedicosByCidade']);

Route::get('/medicos', [MedicController::class, 'index']);
