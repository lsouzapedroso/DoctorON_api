<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/api/unauthenticated.php';
require __DIR__.'/api/authenticated.php';

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

Route::get('/health', function () {
    return response()->json([
        'external_communication' => rescue(function () {
            (new \GuzzleHttp\Client)->get('https://www.google.com');

            return true;
        }, false),
    ]);
});
