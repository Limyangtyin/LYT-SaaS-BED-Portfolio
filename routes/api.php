<?php

use App\Http\Controllers\CompaniesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {
    Route::get('companies', [CompaniesController::class, 'index'])->name('company.index');
    Route::get('companies/{id}', [CompaniesController::class, 'show'])->name('company.show');
    Route::post('companies', [CompaniesController::class, 'store'])->name('company.store');
    Route::put('companies/{id}', [CompaniesController::class, 'update'])->name('company.update');
});
