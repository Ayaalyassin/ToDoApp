<?php

use App\Http\Controllers\Api\AuthUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\WorkTimeController;



/*Route::post("/admin/login", [AuthUserController::class, 'login']);//->middleware('VerifyCode');
Route::post("/admin/register", [AuthUserController::class, 'register']);
Route::post('/admin/logout', [AuthUserController::class, 'logout'])->middleware('jwt.verify');
Route::get('/admin/me', [AuthUserController::class, 'me'])->middleware('jwt.verify');*/
