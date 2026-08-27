<?php

use App\Http\Controllers\PermisosController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('users')->group(function () {
    Route::post('login', [UsersController::class, 'login']);
});

Route::middleware('jwt.verify')->group(function () {

    Route::prefix('users')->group(function () {
        Route::post('create', [UsersController::class, 'create'])->middleware('permisos.verify:create-usuarios');
        Route::post('update/{user}', [UsersController::class, 'update'])->middleware('permisos.verify:update-usuarios');
        Route::post('cambiarContraseña', [UsersController::class, 'cambiarContraseña'])->middleware('permisos.verify:update-usuarios');
        Route::post('impersonar', [UsersController::class, 'impersonar'])->middleware('permisos.verify:impersonate-usuarios');
        Route::post('index', [UsersController::class, 'filter'])->middleware('permisos.verify:list-usuarios');
        Route::get('show/{id}', [UsersController::class, 'show'])->middleware('permisos.verify:view-usuarios');
        Route::delete('delete/{id}', [UsersController::class, 'destroy'])->middleware('permisos.verify:delete-usuarios');
        Route::put('restore/{id}', [UsersController::class, 'restore'])->middleware('permisos.verify:update-usuarios');
    });

    Route::prefix('permisos')->group(function () {
        Route::get('index', [PermisosController::class, 'index'])->middleware('permisos.verify:list-usuarios');
        Route::get('index-agrupados', [PermisosController::class, 'indexAgrupados'])->middleware('permisos.verify:list-usuarios');
        Route::get('index-usuario/{usuario}', [PermisosController::class, 'PermisosUsuario'])->middleware('permisos.verify:list-usuarios');
        Route::get('index/{tipoUsuario}', [PermisosController::class, 'index'])->middleware('permisos.verify:list-usuarios');
        Route::get('misPermisos', [PermisosController::class, 'misPermisos']);
        Route::post('agregarPermiso/{id_usuario}', [PermisosController::class, 'agregarPermiso'])->middleware('permisos.verify:create-usuarios');
    });

    Route::prefix('roles')->group(function () {
        Route::get('index', [RolesController::class, 'index'])->middleware('permisos.verify:list-roles');
        Route::get('show/{id}', [RolesController::class, 'show'])->middleware('permisos.verify:view-roles');
        Route::post('create', [RolesController::class, 'store'])->middleware('permisos.verify:create-roles');
        Route::post('update/{id}', [RolesController::class, 'update'])->middleware('permisos.verify:update-roles');
        Route::post('delete/{id}', [RolesController::class, 'delete'])->middleware('permisos.verify:delete-roles');
    });
});