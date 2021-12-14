<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TodoController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['prefix' => 'auth'], function (){
    Route::post('register', [UserController::class , 'register']);
    Route::post('login',[UserController::class , 'login']);
    Route::post('reset-password-request', [UserController::class , 'resetPasswordRequest']);
    Route::get('/get-all-todo'  , [TodoController::class , 'index']);
    Route::get('/get-todo'  , [TodoController::class , 'owns']);
    Route::delete('/todo/{id}'  , [TodoController::class , 'delete']);
    Route::put('/todo/{id}'  , [TodoController::class , 'update']);
    Route::put('/in-complet-todo/{id}'  , [TodoController::class , 'incomplete']);
    Route::post('/create'  , [TodoController::class , 'store']);
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('logout',[UserController::class , 'logout']);


    });
});
