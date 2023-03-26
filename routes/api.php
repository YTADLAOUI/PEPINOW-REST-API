<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

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

// dd($user);
Route::post('/register', [UserController::class,'register']);
Route::post('/login', [UserController::class,'authenticate']);

 
Route::group(['middleware' => ['jwt.vendeur.verfiy']], function() {
  Route::apiResource('/plant', PlantController::class)->except(['index','show']);
});
Route::group(['middleware' => ['jwt.admins.verfiy']],function(){
  Route::apiResource('categorie',CategorieController::class);
  Route::put('changeRole/{user}',[UserController::class,'update']);
});
Route::group(['middleware' => ['jwt.verify']], function() {
  Route::get('plant',[PlantController::class,'index']);
  Route::get('/plant/{plant}',[PlantController::class,'show']);
  Route::post('logout', [UserController::class,'logout']);
  Route::put('profileUpdate',[UserController::class,'updateProfile']);
  Route::post('profile/rest-password',[UserController::class,'resetPassword']);
});


Route::post('forget-password',[UserController::class,'forgotPassword']);
Route::post('rest-password',[UserController::class,'reset'])->name('password.reset');

// Route::apiResource('categorie',CategorieController::class);
// // Route::put('changeRole',[UserController::class,'update']);
// Route::get('plant',[PlantController::class,'index']);
//   Route::get('/plant/{plant}',[PlantController::class,'show']);
//   Route::post('logout', [UserController::class,'logout']);
//   Route::put('profileUpdate',[UserController::class,'updateProfile']);
//   Route::apiResource('/plant', PlantController::class)->except(['index','show']);