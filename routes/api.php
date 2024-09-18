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


Route::group(['prefix' => 'v1'], function () {
    Route::get('companies', [CompaniesController::class, 'index'])->name('company.index');
    Route::get('companies/{id}', [CompaniesController::class, 'show'])->name('company.show');
    Route::post('companies', [CompaniesController::class, 'store'])->name('company.store');
    Route::put('companies/{id}/update', [CompaniesController::class, 'update'])->name('company.update');
    Route::delete('companies/{id}/delete', [CompaniesController::class, 'destroy'])->name('company.delete');
    Route::delete('companies/delete-all', [CompaniesController::class, 'destroyAll'])->name('company.delete-all');
    Route::put('companies/{id}/restore', [CompaniesController::class, 'restore'])->name('company.restore');
    Route::put('companies/restore-all', [CompaniesController::class, 'restoreAll'])->name('company.restore-all');
    Route::put('companies/{id}/removeTrash', [CompaniesController::class, 'removeFromTrash'])->name('company.removeTrash');
    Route::put('companies/removeTrash-all', [CompaniesController::class, 'removeAllFromTrash'])->name('company.removeTrash-all');

    Route::get('positions', [PositionsController::class, 'index'])->name('position.index');
    Route::get('positions/{id}', [PositionsController::class, 'show'])->name('position.show');
    Route::post('positions', [PositionsController::class, 'store'])->name('position.store');
    Route::put('positions/{id}/update', [PositionsController::class, 'update'])->name('position.update');
    Route::delete('positions/{id}/delete', [PositionsController::class, 'destroy'])->name('position.delete');
    Route::delete('positions/delete-all', [PositionsController::class, 'destroyAll'])->name('position.delete-all');
    Route::put('positions/{id}/restore', [PositionsController::class, 'restore'])->name('position.restore');
    Route::put('positions/restore-all', [PositionsController::class, 'restoreAll'])->name('position.restore-all');
    Route::put('positions/{id}/removeTrash', [PositionsController::class, 'removeFromTrash'])->name('position.removeTrash');
    Route::put('positions/removeTrash-all', [PositionsController::class, 'removeAllFromTrash'])->name('position.removeTrash-all');

    Route::get('users', [UsersController::class, 'index'])->name('user.index');
    Route::get('users/{id}', [UsersController::class, 'show'])->name('user.show');
    Route::post('users', [UsersController::class, 'store'])->name('user.store');
    Route::put('users/{id}/update', [UsersController::class, 'update'])->name('user.update');
    Route::delete('users/{id}/delete', [UsersController::class, 'destroy'])->name('user.delete');
    Route::put('users/{id}/restore', [UsersController::class, 'restore'])->name('user.restore');
});
