<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//usar como ejemplo para importar los nuevos controladores

//use App\Http\Controllers\

use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\AccionJugadorController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\PosicionController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\UsuarioController;

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

//Login 
//no va dentro del middleware
Route::post('login', [LoginController::class, 'login']);

//configuraciones
Route::resource('configuracion', ConfiguracionController::class);

//disciplinas
Route::resource('disciplinas', DisciplinaController::class);

// Accion Jugador 
Route::resource('accion_jugador', AccionJugadorController::class);

//jugadores
Route::resource('jugadores', JugadorController::class);
Route::post('edit-foto-jugador/{id}', [JugadorController::class, 'editarFotoJugador']);

// Posiciones
Route::apiResource('posiciones', PosicionController::class);


// Usuarios
Route::apiResource('usuarios', UsuarioController::class);
Route::get('organizadores', [UsuarioController::class, 'getOrganizadores']);
Route::post('edit-email-usuario/{id}', [UsuarioController::class, 'editarEmailUsuario']);
Route::post('edit-password-usuario/{id}', [UsuarioController::class, 'editarPasswordUsuario']);
Route::post('edit-foto-usuario/{id}', [UsuarioController::class, 'editarFotoUsuario']);


//las que van con token
Route::middleware('auth:sanctum')->group(function () {


});








// -----------------------------------------------------------------------------------------

# ******************************************
#             RUTAS DE PRUEBA
# ******************************************

Route::get('getConfiguracionGrupo', [GrupoController::class, 'getConfiguracion']);

