<?php

use App\Http\Controllers\Api\AuthUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\WorkTimeController;
use App\Http\Controllers\ResetCodePasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post("login", [AuthUserController::class, 'login']);//->middleware('VerifyCode');
Route::post("register", [AuthUserController::class, 'register']);
Route::post('logout', [AuthUserController::class, 'logout'])->middleware('jwt.verify');
Route::get('me', [AuthUserController::class, 'me'])->middleware('jwt.verify');

Route::post('password/email', [ResetCodePasswordController::class, 'forgotpassword']);
Route::post('password/code/check', [ResetCodePasswordController::class, 'CodeCheck']);
Route::post('password/reset', [ResetCodePasswordController::class, 'passwordset']);


Route::get('testemail', [ResetCodePasswordController::class, 'testemail']);


Route::post("/admin/login", [AdminController::class, 'login']);//->middleware('VerifyCode');
Route::post("/admin/register", [AdminController::class, 'register']);
Route::post('/admin/logout', [AdminController::class, 'logout'])->middleware('jwt.verify');
Route::get('/admin/me', [AdminController::class, 'me'])->middleware('jwt.verify');




Route::group(['middleware' => ['jwt.verify']], function () {

    //Route::post("login", [AuthUserController::class, 'login']);//->middleware('VerifyCode');

    Route::group(['prefix' => 'profiles'], function () {
        Route::get('', [ProfileController::class, 'index']);
        Route::post('', [ProfileController::class, 'store']);
        Route::post('/update', [ProfileController::class, 'update']);
        Route::get('{id}', [ProfileController::class, 'show']);
        Route::delete('delete', [ProfileController::class, 'destroy']);
    });

    Route::group(['prefix' => 'projects'], function () {
        Route::get('', [ProjectController::class, 'index']);
        Route::post('', [ProjectController::class, 'store']);
        Route::post('/{id}', [ProjectController::class, 'update']);
        Route::get('{id}', [ProjectController::class, 'show']);
        Route::delete('{id}', [ProjectController::class, 'destroy']);
        Route::post('adduser/{id}',[ProjectController::class, 'adduser']);
    });

    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/{project_id}', [TaskController::class, 'index']);
        Route::post('', [TaskController::class, 'store']);
        Route::post('/{id}', [TaskController::class, 'update']);
        Route::get('{id}', [TaskController::class, 'show']);
        Route::delete('{id}', [TaskController::class, 'destroy']);
    });

    ## For Admin
    //Route::group(['prefix' => 'admin','middleware' => ['role:admin']], function () {

        Route::group(['prefix' => 'roles'], function () {
            Route::get('', [RoleController::class, 'index']);
            Route::post('', [RoleController::class, 'store'])->middleware("role:admin");
            Route::post('/{id}', [RoleController::class, 'update']);
            Route::get('{id}', [RoleController::class, 'show']);
            Route::delete('{id}', [RoleController::class, 'destroy']);
            //Route::post('/addtouser',[UserRoleController::class,'store']);
            //Route::post('/addtouser',[RoleController::class,'addtouser']);
            //Route::post('/testRole',[RoleController::class,'testRole']);
        });

        Route::post('/addtouser',[RoleController::class,'addtouser']);
        Route::post('/testRole',[RoleController::class,'testRole']);

        Route::group(['prefix' => 'permissions'], function () {
            Route::get('', [PermissionController::class, 'index']);
            Route::post('', [PermissionController::class, 'store']);
            Route::post('/{id}', [PermissionController::class, 'update']);
            Route::get('{id}', [PermissionController::class, 'show']);
            Route::delete('{id}', [PermissionController::class, 'destroy']);
            //Route::post('/getrole', [PermissionController::class, 'getrole']);
            //Route::post('/addpermissiontorole', [PermissionController::class, 'addpermissiontorole']);
            //Route::post('/updatepermissionfromrole/{id}', [PermissionController::class, 'updatepermissionfromrole']);
            //Route::post('/deletepermissionfromrole/{id}', [PermissionController::class, 'deletepermissionfromrole']);

        });
        Route::post('/getrole', [PermissionController::class, 'getrole']);
        Route::post('/addpermissiontorole', [PermissionController::class, 'addpermissiontorole']);
        Route::post('/updatepermissionfromrole/{id}', [PermissionController::class, 'updatepermissionfromrole']);
        Route::post('/deletepermissionfromrole/{id}', [PermissionController::class, 'deletepermissionfromrole']);
        Route::post('/testPermission', [PermissionController::class, 'testPermission']);
    //});


    ## For User
    Route::group(['prefix' => 'user','middleware' => ['role:user']], function () {

    });


Route::get('/sendEmail',[EmailController::class,'send']);
//Route::get('/setcode',[AuthUserController::class,'setcode']);
Route::post('/setcode',[AuthUserController::class,'setcode']);


Route::group(['prefix' => 'worktimes'], function () {
    Route::get('', [WorkTimeController::class, 'index']);
    Route::post('', [WorkTimeController::class, 'store']);//->middleware("role:admin");
    Route::post('/{id}', [WorkTimeController::class, 'update']);
    Route::get('{id}', [WorkTimeController::class, 'show']);
    Route::delete('{id}', [WorkTimeController::class, 'destroy']);

});


});
//Route::post('/setcode',[AuthUserController::class,'setcode']);


