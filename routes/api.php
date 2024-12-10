<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckListController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::resource('checklist', CheckListController::class)->only('index', 'store', 'destroy');

    Route::prefix('checklist')->group(function () {
        Route::get('/{checklist}/item', [CheckListController::class, 'getCheckListItems']);
        Route::post('/{checklist}/item', [CheckListController::class, 'storeCheckListItem']);
        Route::get('/{checklist}/item/{checklistItemId}', [CheckListController::class, 'showCheckListItem']);
        Route::put('/{checklist}/item/{checklistItemId}', [CheckListController::class, 'updateStatusCheckListItem']);
        Route::delete('/{checklist}/item/{checklistItemId}', [CheckListController::class, 'deleteCheckListItem']);
        Route::put('/{checklist}/item/rename/{checklistItemId}', [CheckListController::class, 'renameCheckListItem']);
    });
});
