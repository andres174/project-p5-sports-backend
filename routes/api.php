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
Route::post('deleteSelectConfiguracion', [ConfiguracionController::class, 'deleteSelectConfiguraciones']);

//disciplinas
Route::resource('disciplinas', DisciplinaController::class);
Route::post('deleteSelectDisciplina', [DisciplinaController::class, 'deleteSelectDisciplinas']);
// Accion Jugador 
Route::resource('accion_jugador', AccionJugadorController::class);

//jugadores
Route::resource('jugadores', JugadorController::class);
Route::post('edit-foto-jugador/{id}', [JugadorController::class, 'editarFotoJugador']);
Route::post('deleteSelectjugador', [JugadorController::class, 'deleteSelectJugador']);

// Posiciones
Route::apiResource('posiciones', PosicionController::class);
Route::post('deleteSelectposicion', [PosicionController::class, 'deleteSelectPosicion']);

// Usuarios
Route::apiResource('usuarios', UsuarioController::class);
Route::get('organizadores', [UsuarioController::class, 'getOrganizadores']);
Route::post('edit-email-usuario/{id}', [UsuarioController::class, 'editarEmailUsuario']);
Route::post('edit-password-usuario/{id}', [UsuarioController::class, 'editarPasswordUsuario']);
Route::post('edit-foto-usuario/{id}', [UsuarioController::class, 'editarFotoUsuario']);
Route::post('deleteSelectedUsuarios', [UsuarioController::class, 'eliminarUsuarios']);


//  Grupos
Route::get('get-all-eventos', [GrupoController::class, 'getAllEventos']);
Route::get('get-all-eventos-discplinas', [GrupoController::class, 'getAllEventoDisciplinas']);
Route::get('get-all-equipos-discplinas', [GrupoController::class, 'getAllEquipoDisciplinas']);



//las que van con token
Route::middleware('auth:sanctum')->group(function () {


});








// -----------------------------------------------------------------------------------------

# ******************************************
#             RUTAS DE PRUEBA
# ******************************************

Route::get('getConfiguracionGrupo', [GrupoController::class, 'getConfiguracion']);

