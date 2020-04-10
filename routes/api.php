<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
    Route::resource('profiles', 'ProfileController', ['except' => ['create', 'edit']]);
    Route::resource('images', 'ProfileImagesController', ['except' => ['create', 'edit']]);
    Route::post('profiles/{id}', 'ProfileController@update');
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'middleware' => 'api.auth',
    'providers'  => [
        'jwt',
        'basic',
    ],
    'prefix'     => 'api',
], function (\Dingo\Api\Routing\Router $api) {
    $api->resource('/', 'App\Http\Controllers\API\V1\ProfileController', [
        'only' => [
            'index',
            'store',
            'show',
            'delete',
        ],
    ]);
    $api->resource('/images', 'App\Http\Controllers\API\V1\ProfileImagesController', [
        'only' => [
            'delete',
        ],
    ]);
});



