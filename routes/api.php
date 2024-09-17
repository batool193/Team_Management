<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\ProjectController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);


        Route::apiResource('projects', ProjectController::class);
        Route::post('projects/{project}/{user}/{role}', [ProjectController::class, 'AddUserToProject']);
        Route::get('projects/{project}/latestTask', [ProjectController::class, 'GetLatestTask']);
        Route::get('projects/{project}/oldTask', [ProjectController::class, 'GetOldestTask']);
        Route::get('projects/{project}/{title}', [ProjectController::class, 'HighestPriorityTask']);

        Route::apiResource('tasks', TaskController::class);
        Route::get('tasks/{task}/restore', [TaskController::class, 'RestoreDeletedTask']);
        Route::post('tasks/{task}/updatestatus', [TaskController::class, 'updateStatus']);
        Route::post('tasks/{task}', [TaskController::class,'AddNote']);

        Route::middleware(EnsureUserIsAdmin::class)->group(function () {
            Route::apiResource('users', UserController::class);
            Route::get('users/{user}/restore', [UserController::class, 'RestoreDeletedUser']);
            Route::get('projects/{project}/restore', [ProjectController::class, 'RestoreDeletedProject']);
            });

    });
