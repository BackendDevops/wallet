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

/*
 *
 * Login Routes
 */
Route::prefix('backoffice')->group(function(){
    Route::post('login','App\Http\Controllers\API\AuthController@login');
    Route::post('register','App\Http\Controllers\API\AuthController@register');
    /*
 *  Protected Routes
 */
    Route::middleware('jwt.verify')->group(function(){
        Route::get('promotion-codes','App\Http\Controllers\API\PromoCodeController@index');
        Route::get('promotion-codes/{id}','App\Http\Controllers\API\PromoCodeController@find');
        Route::post('promotion-codes','App\Http\Controllers\API\PromoCodeController@create');
        Route::post('assign-promotion','App\Http\Controllers\API\PromoCodeController@assign');
        Route::get('logout','App\Http\Controllers\API\AuthController@logout');
        Route::get('renew','App\Http\Controllers\API\AuthController@renew');
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
