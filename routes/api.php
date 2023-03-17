<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
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

Route::post('/register', [UserController::class,'register']);
Route::post('/login', [UserController::class,'authenticate']);
// Route::get('open', 'DataController@open');
// Route::apiResource('/plant', PlantController::class);
Route::apiResource('/categorie', CategorieController::class);
Route::group(['middleware' => ['jwt.admin.verfiy']], function() {

});
Route::group(['middleware' => ['jwt.user.verfiy']], function() {
});

Route::group(['middleware' => ['jwt.verify']], function() {
  Route::apiResource('/plant', PlantController::class);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
