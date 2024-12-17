<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\WorkUnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
    Route::apiResource('employees', EmployeeController::class)->middleware('auth:api');
    Route::get('employees-search', [EmployeeController::class, 'search'])->middleware('auth:api');
    Route::post('employees/pdf', [EmployeeController::class, 'generatePdf'])->middleware('auth:api');
    Route::apiResource('work-units', WorkUnitController::class)->middleware('auth:api');
    Route::Get('work-units/all', [WorkUnitController::class, 'getAll'])->middleware('auth:api');
});
