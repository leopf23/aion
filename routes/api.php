<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/hello', function (Request $request) {
    return response('Hello World', 200);
})->name('hello');

Route::get('/areas', [App\Http\Controllers\areaController::class, 'findAll'])->name('areas.findAll');
Route::get('/departamentos/{area_id}', [App\Http\Controllers\departamentoController::class, 'byArea'])->name('departamentos.byArea');
Route::get('/procesos/{departamento_id}', [App\Http\Controllers\procesoController::class, 'byDepartamento'])->name('procesos.byDepartamento');
Route::get('/procedimientos/{proceso_id}', [App\Http\Controllers\procedimientoController::class, 'byProceso'])->name('procedimientos.byProceso');
Route::get('/departamentos', [App\Http\Controllers\departamentoController::class, 'findAll'])->name('departamentos.findAll');
Route::get('/usuarios', [App\Http\Controllers\userController::class, 'findAll'])->name('users.findAll');
Route::get('/usuarios/all/todo', [App\Http\Controllers\userController::class, 'all'])->name('users.all');
Route::get('/usuarios/{area_id}', [App\Http\Controllers\userController::class, 'byArea'])->name('usuarios.byArea');



Route::get('/challenges', [App\Http\Controllers\challengesController::class, 'findAll'])->name('challenges.findAll');

Route::get('/config-dashboard', [App\Http\Controllers\dashboardController::class, 'getConfig'])->name('config.getConfig');

Route::get('/challenges/{departamento_id}', [App\Http\Controllers\challengesController::class, 'byDepartamento'])->name('challenges.byArea');
Route::get('/opciones', [App\Http\Controllers\opcionController::class, 'findAll'])->name('opciones.findAll');
Route::get('/objetivos', [App\Http\Controllers\objetivosController::class, 'findAll'])->name('objetivos.findAll');
Route::get('/minutas', [App\Http\Controllers\minutasController::class, 'findAll'])->name('minutas.findAll');
Route::get('/procesos', [App\Http\Controllers\procesoController::class, 'findAll'])->name('procesos.findAll');
Route::get('/procedimientos', [App\Http\Controllers\procedimientoController::class, 'findAll'])->name('procedimientos.findAll');
Route::get('/estandares', [App\Http\Controllers\EstandarController::class, 'findAll'])->name('estandares.findAll');
Route::get('/kpis', [App\Http\Controllers\kpiController::class, 'findAll'])->name('kpis.findAll');
Route::get('/kpis/{departamento_id}/departamento', [App\Http\Controllers\kpiController::class, 'byDepartamento'])->name('kpis.byDepartamento');
Route::get('/kpis/{kpi}/find', [App\Http\Controllers\kpiController::class, 'byId'])->name('kpis.byId');
Route::get('/roles', [App\Http\Controllers\rolesController::class, 'findAll'])->name('roles.findAll');
Route::get('/permisos', [App\Http\Controllers\permisosController::class, 'findAll'])->name('permisos.findAll');
Route::get('/permisos/all', [App\Http\Controllers\permisosController::class, 'all'])->name('permisos.All');

Route::get('/tareas', [App\Http\Controllers\tareasController::class, 'findAll'])->name('tareas.findAll');
Route::get('/tareas/user', [App\Http\Controllers\tareasController::class, 'byUser'])->name('tareas.byUser');
Route::get('/tareas/toReview', [App\Http\Controllers\tareasController::class, 'toReview'])->name('tareas.toReview');
Route::get('/tareas/{minuta_id}', [App\Http\Controllers\tareasController::class, 'byMinuta'])->name('tareas.byMinuta');
Route::get('/tareas/counter/{minuta_id}', [App\Http\Controllers\tareasController::class, 'countTareas'])->name('tareas.counter');
Route::get('/tareas/terminadas/counter/{minuta_id}', [App\Http\Controllers\tareasController::class, 'countTareasTerminadas'])->name('tareas.terminadas');
Route::patch('/tareas/validar/{tarea}', [App\Http\Controllers\tareasController::class, 'validar'])->name('tareas.validar');


Route::get('/getDepartamentos', [App\Http\Controllers\homeMetroController::class, 'getDepartamentos'])->name('getDepartamentos.find');
Route::get('/getProcesos', [App\Http\Controllers\homeMetroController::class, 'getProcesos'])->name('getProcesos.find');
Route::get('/getProcedimientos', [App\Http\Controllers\homeMetroController::class, 'getProcedimientos'])->name('getProcedimientos.find');
Route::get('/getEstandares', [App\Http\Controllers\homeMetroController::class, 'getEstandares'])->name('getEstandares.find');






Route::get('/asistentes/{id}', [App\Http\Controllers\asistenteController::class, 'findByMinutaId'])->name('asistente.findByMinutaId');

// Route::get('/objetivos/{objetivos_id}', [App\Http\Controllers\objetivosController::class, 'byDepartamento'])->name('challenges.byArea');
Route::post('/asistente/store', [App\Http\Controllers\asistenteController::class, 'store'])->name('asistentes.store');
Route::delete('/asistente/{asistente}', [App\Http\Controllers\asistenteController::class, 'destroy'])->name('asistente.delete');
// Sistema de gestión
Route::get('/sysgestion', [App\Http\Controllers\sysGestionController::class, 'kpis'])->name('sysgestion.kpis');
Route::get('/sysgestion/{departamento_id}/departamento', [App\Http\Controllers\sysGestionController::class, 'departamento'])->name('sysgestion.departamento');


// Tipos de minutas
Route::get('/tipo-minuta', [App\Http\Controllers\tipoMinutaController::class, 'index'])->name('tipo-minuta.index');
Route::get('/tipo-minuta/{tipoMinuta}/edit', [App\Http\Controllers\tipoMinutaController::class, 'edit'])->name('tipo-minuta.edit');
Route::post('/tipo-minuta', [App\Http\Controllers\tipoMinutaController::class, 'store'])->name('tipo-minuta.store');
Route::patch('/tipo-minuta{tipoMinuta}/update', [App\Http\Controllers\tipoMinutaController::class, 'update'])->name('tipo-minuta.update');
Route::delete('/tipo-minuta/{tipoMinuta}/delete', [App\Http\Controllers\tipoMinutaController::class, 'destroy'])->name('tipo-minuta.destroy');

// Acciones
Route::get('/acciones', [App\Http\Controllers\accionController::class, 'index'])->name('acciones.index');
Route::get('/acciones/{departamento_id}/pilar/{tipo}/tipo', [App\Http\Controllers\accionController::class, 'byDepartamento'])->name('acciones.byDepartamento');
Route::get('/acciones/{accion}/edit', [App\Http\Controllers\accionController::class, 'edit'])->name('acciones.edit');
Route::post('/acciones/store', [App\Http\Controllers\accionController::class, 'store'])->name('acciones.store');
Route::patch('/acciones/{accion}/update', [App\Http\Controllers\accionController::class, 'update'])->name('acciones.update');
Route::delete('/acciones/{accion}/delete', [App\Http\Controllers\accionController::class, 'destroy'])->name('acciones.destroy');

Route::get('/sysgestion/{id}/registros_kpi', [App\Http\Controllers\registros_kpisController::class, 'registros'])->name('registros_kpi.registros');
Route::get('/sysgestion/{registros_kpi}/edit', [App\Http\Controllers\registros_kpisController::class, 'edit'])->name('registros_kpi.edit');

// Notificaciones
Route::get('/notificaciones', [App\Http\Controllers\notificacionController::class, 'getByUser'])->name('notificaciones.getByUser');
Route::get('/notificaciones/markAsRead', [App\Http\Controllers\notificacionController::class, 'markAsRead'])->name('notificaciones.markAsRead');
Route::get('/notificaciones/deleteReaded', [App\Http\Controllers\notificacionController::class, 'deleteReaded'])->name('notificaciones.deleteReaded');

// Route::get('send/mail', [App\Http\Controllers\SendMailController::class, 'sendMailWithAttachment'])->name('mailer.sendMailWithAttachment');
Route::get('send/tareas/mail', [App\Http\Controllers\SendMailController::class, 'recordatorioTarea'])->name('mailer.recordatorioTarea');
Route::get('send/{minuta}/mail', [App\Http\Controllers\SendMailController::class, 'sendTareasByMinuta'])->name('mailer.sendTareasByMinuta');

//Encargado de flujo
Route::get('/encargado-flujo', [App\Http\Controllers\encargado_flujoController::class, 'findAll'])->name('encargado.findAll');

//Reportes
Route::get('/getFlujo', [App\Http\Controllers\reportesController::class, 'findAll'])->name('getFlujo.findAll');
Route::get('/getReporteSemanal', [App\Http\Controllers\reportesController::class, 'findAllReporteSemanal'])->name('getFlujo.findAllReporteSemanal');
Route::get('/getReportes/{id}', [App\Http\Controllers\reportesController::class, 'findAllReportes'])->name('getReportes.findAllReportes');
