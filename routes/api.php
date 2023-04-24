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
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoDisciplinaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JugadorEquipoController;
use App\Http\Controllers\ResultadoController;

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

//Evento
Route::resource('Evento', EventoController::class);
Route::post('edit-imagen-evento/{id}', [EventoController::class, 'EditarImagenEvento']);
Route::post('deleteselectevento', [EventoController::class, 'deleteSelectEvento']);

//Evento diciplina
Route::resource('EventoDisciplina', EventoDisciplinaController::class);
Route::get('get-eventos', [EventoDisciplinaController::class, 'getEventos']);
Route::get('get-disciplinas', [EventoDisciplinaController::class, 'getDisciplina']);
Route::get('get-config', [EventoDisciplinaController::class, 'getConfiguracion']);
Route::get('mostrar-eventos-discplinas', [EventoDisciplinaController::class, 'MostrarEventoDisciplinas']);


// Accion Jugador 
Route::resource('accion_jugador', AccionJugadorController::class);

//equipo
Route::apiResource('equipos', EquipoController::class);
Route::post('edit-logo-equipo/{id}', [EquipoController::class, 'editarLogoEquipo']);
Route::post('delete-selected-equipos', [EquipoController::class, 'deleteSelectedEquipos']);

//jugadores
Route::resource('jugadores', JugadorController::class);
Route::post('edit-foto-jugador/{id}', [JugadorController::class, 'editarFotoJugador']);
Route::post('deleteSelectjugador', [JugadorController::class, 'deleteSelectJugador']);

//Resultado
Route::post('deleteselectresultados', [ResultadoController::class, 'deleteSelectResultados']);
Route::get('tablaposicion/{id}', [ResultadoController::class, 'tablePosition']);
Route::get('getgroupDiscipline/{id}', [ResultadoController::class, 'getGroupDiscipline']);
Route::resource('resultado', ResultadoController::class);
Route::get('tablaposiciones/{id}', [ResultadoController::class, 'tablePositiones']);

// Posiciones
Route::apiResource('posiciones', PosicionController::class);
Route::post('deleteSelectposicion', [PosicionController::class, 'deleteSelectPosicion']);

// Usuarios
Route::apiResource('usuarios', UsuarioController::class);
Route::get('organizadores', [UsuarioController::class, 'getOrganizadores']);
Route::post('edit-email-usuario/{id}', [UsuarioController::class, 'editarEmailUsuario']);
Route::post('edit-password-usuario/{id}', [UsuarioController::class, 'editarPasswordUsuario']);
Route::post('edit-foto-usuario/{id}', [UsuarioController::class, 'editarFotoUsuario']);
Route::post('delete-selected-usuarios', [UsuarioController::class, 'eliminarUsuarios']);

// Jugador Equipo
Route::get(
  'get-eventos-from-organizador/{id_organizador}',
  [JugadorEquipoController::class, 'getEventosFromOrganizador']
);
Route::get(
  'get-evento-disciplinas-small-from-evento/{id_evento}',
  [JugadorEquipoController::class, 'getEventoDisciplinasSmallFromEvento']
);


//  Grupos
Route::get('get-all-eventos', [GrupoController::class, 'getAllEventos']);
Route::get('get-all-eventos-discplinas', [GrupoController::class, 'getAllEventoDisciplinas']);
Route::get('get-one-eventos-discplinas/{id}', [GrupoController::class, 'getOneEventoDisciplina']);
Route::get('get-all-equipos-discplinas', [GrupoController::class, 'getAllEquipoDisciplinas']);
Route::get('get-equipos-discplinas/{id}', [GrupoController::class, 'getEquiposFormOneDisciplina']);
Route::get('get-config-eventos-discplina/{id}', [GrupoController::class, 'getConfiguracionFromEventoDisciplina']);
Route::post('generar-grupos-auto', [GrupoController::class, 'guardarGruposGenerados']);

//Partidos
Route::get('pruebaHora/{id}', [PartidoController::class, 'crearPartidos']);





//las que van con token
Route::middleware('auth:sanctum')->group(function () {
});








// -----------------------------------------------------------------------------------------

# ******************************************
#             RUTAS DE PRUEBA
# ******************************************

Route::get('getConfiguracionGrupo', [GrupoController::class, 'getConfiguracion']);
