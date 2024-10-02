<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::apiResource('positions', PositionsController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('positions', PositionsController::class)
    ->only(['index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::apiResource('companies', CompaniesController::class);
        Route::put('companies/{id}/restore', [CompaniesController::class, 'restore'])->name('company.restore');
        Route::put('companies/restore-all', [CompaniesController::class, 'restoreAll'])->name('company.restore-all');
        Route::put('companies/{id}/removeTrash', [CompaniesController::class, 'removeFromTrash'])->name('company.removeTrash');
        Route::put('companies/removeTrash-all', [CompaniesController::class, 'removeAllFromTrash'])->name('company.removeTrash-all');

        Route::apiResource('positions', PositionsController::class);
        Route::put('positions/{id}/restore', [PositionsController::class, 'restore'])->name('position.restore');
        Route::put('positions/restore-all', [PositionsController::class, 'restoreAll'])->name('position.restore-all');
        Route::put('positions/{id}/removeTrash', [PositionsController::class, 'removeFromTrash'])->name('position.removeTrash');
        Route::put('positions/removeTrash-all', [PositionsController::class, 'removeAllFromTrash'])->name('position.removeTrash-all');

        Route::apiResource('users', UsersController::class);
        Route::put('users/{id}/restore', [UsersController::class, 'restore'])->name('user.restore');
        Route::put('users/restore-all', [UsersController::class, 'restoreAll'])->name('user.restore-all');
        Route::put('users/{id}/removeTrash', [UsersController::class, 'removeFromTrash'])->name('user.removeTrash');
        Route::put('users/removeTrash-all', [UsersController::class, 'removeAllFromTrash'])->name('user.removeTrash-all');
    });
});
