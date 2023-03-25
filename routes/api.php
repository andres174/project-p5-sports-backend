<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\AccionJugadorController;
//use App\Http\Controllers
use App\Http\Controllers\JugadorController;
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
//configuraciones
Route::resource('configuracion',ConfiguracionController::class);
// Accion Jugador 
Route::resource('accion_jugador',AccionJugadorController::class);

//jugadores
Route::resource('jugadores', JugadorController::class); 
Route::post('edit-foto-jugador/{id}', [JugadorController::class, 'editarFotoJugador']);

Route::middleware('auth:sanctum')->group ( function () {


});
