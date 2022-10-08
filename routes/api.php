<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\VenteController;
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
Route::middleware(['cors'])->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post("/login", [UserController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function() {
        Route::post("/register", [UserController::class, 'createUser']);
        Route::get("/users", [UserController::class, "getUsers"]);
        Route::put("/users/{id}/update", [UserController::class, "editUser"]);
        Route::put("/users/{id}/delete", [UserController::class, "deleteUser"]);
    
        Route::prefix('/category')->group(function() {
            Route::get('', [CategoryController::class, 'index']);
            Route::post('/store', [CategoryController::class, 'store']);
            Route::put('/{id}/update', [CategoryController::class, 'update']);
            Route::delete('/{id}/delete', [CategoryController::class, 'destroy']);
            Route::put('/{id}/restore', [CategoryController::class, 'restore']);
        });
        
        Route::prefix('/sub-category')->group(function() {
            Route::get('', [SubCategoryController::class, 'index']);
            Route::post('/store', [SubCategoryController::class, 'store']);
            Route::put('/{id}/update', [SubCategoryController::class, 'update']);
            Route::delete('/{id}/delete', [SubCategoryController::class, 'destroy']);
            Route::put('/{id}/restore', [SubCategoryController::class, 'restore']);
            Route::get('/{id}/by-category', [SubCategoryController::class, 'getSubCategoriesByIdCat']);
        });

        Route::prefix('/vente')->group(function() {
            Route::get('', [VenteController::class, 'index']);
            Route::post('/store', [VenteController::class, 'store']);
        });
    });
});


