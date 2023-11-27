<?php

use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\AsistenciasController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\DocentesController;
use App\Http\Controllers\SeleccionesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'alumnos'], function () {
    Route::get('/', [AlumnosController::class, 'index']);
    Route::post('/create', [AlumnosController::class, 'create']);
    Route::get('/{id}', [AlumnosController::class, 'show']);
    Route::put('/update', [AlumnosController::class, 'update']);
    Route::delete('/{id}', [AlumnosController::class, 'destroy']);
});

Route::group(['prefix' => 'docentes'], function () {
    Route::get('/', [DocentesController::class, 'index']);
    Route::post('/create', [DocentesController::class, 'create']);
    Route::get('/{id}', [DocentesController::class, 'show']);
    Route::put('/update', [DocentesController::class, 'update']);
    Route::delete('/{id}', [DocentesController::class, 'destroy']);
});

Route::group(['prefix' => 'cursos'], function () {
    Route::get('/', [CursosController::class, 'index']);
    Route::post('/create', [CursosController::class, 'create']);
    Route::get('/{id}', [CursosController::class, 'show']);
    Route::put('/update', [CursosController::class, 'update']);
    Route::delete('/{id}', [CursosController::class, 'destroy']);
});


Route::group(['prefix' => 'seleccion'], function () {
    Route::post('/', [SeleccionesController::class, 'create']);
    Route::delete('/', [SeleccionesController::class, 'destroy']);
    Route::post('/asistencia', [AsistenciasController::class, 'create']);
    Route::put('/asistencia', [AsistenciasController::class, 'update']);
});



