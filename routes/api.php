<?php

use App\Http\Controllers\CoordinadorInstitucionalController;
use App\Http\Controllers\CoordinadorTutoriasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\TutoresController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'coordinadorInstiucional'], function () {
    Route::post('/crearGeneraciones', [CoordinadorInstitucionalController::class, 'crearGeneraciones']);
    Route::get('/obtenerGeneraciones', [CoordinadorInstitucionalController::class, 'obtenerGeneraciones']);
    Route::get('/obtenerCarreras', [CoordinadorInstitucionalController::class, 'obtenerCarreras']);
    Route::post('/obtenerPeriodosFechasGeneracion', [CoordinadorInstitucionalController::class, 'obtenerPeriodosFechasGeneracion']);
    Route::post('/actualizarFechasGeneracion', [CoordinadorInstitucionalController::class, 'actualizarFechasGeneracion']);
    Route::post('/cargarNuevosAlumnos', [CoordinadorInstitucionalController::class, 'cargarNuevosAlumnos']);
    Route::post('/cargarDocentes', [CoordinadorInstitucionalController::class, 'cargarDocentes']);
    Route::post('/obtenerDocentesCarrera', [CoordinadorInstitucionalController::class, 'obtenerDocentesCarrera']);
    Route::post('/asignarTutorCarrera', [CoordinadorInstitucionalController::class, 'asignarTutorCarrera']);
    Route::post('/generarReportesSemestrales', [CoordinadorInstitucionalController::class, 'generarReportesSemestrales']);
    Route::post('/verificacionInformesCoordinadorInst', [CoordinadorInstitucionalController::class, 'verificacionInformesCoordinadorInst']);
    Route::post('/login', [CoordinadorInstitucionalController::class, 'login']);
    Route::post('/validarUsuario', [CoordinadorInstitucionalController::class, 'validarUsuario']);
    Route::post('/crearAviso', [CoordinadorInstitucionalController::class, 'crearAviso']);
    Route::get('/verAviso', [CoordinadorInstitucionalController::class, 'verAviso']);



});


Route::group(['prefix' => 'coordinadorTutorias'], function () {
    Route::post('/obtenerAlumnosGeneraciones', [CoordinadorTutoriasController::class, 'obtenerAlumnosGeneraciones']);
    Route::get('/obtenerGeneracionesAlumnosAsignados', [CoordinadorTutoriasController::class, 'obtenerGeneracionesAlumnosAsignados']);
    Route::post('/asignacionAlumnosTutorados', [CoordinadorTutoriasController::class, 'asignacionAlumnosTutorados']);
    Route::post('/geneRepMensCoordTut', [CoordinadorTutoriasController::class, 'geneRepMensCoordTut']);
    Route::post('/verAsignaciones', [CoordinadorTutoriasController::class, 'verAsignaciones']);


});
Route::group(['prefix' => 'Tutor'], function () {
    Route::get('/obtenerGeneracionesTutores', [TutoresController::class, 'obtenerGeneracionesTutores']);

    Route::post('/obtenerListaTutorados', [TutoresController::class, 'obtenerListaTutorados']);
    Route::post('/capturarPrimerInforme', [TutoresController::class, 'capturarPrimerInforme']);
    Route::post('/capturarSegundoInforme', [TutoresController::class, 'capturarSegundoInforme']);
    Route::post('/capturarTercerInforme', [TutoresController::class, 'capturarTercerInforme']);

    Route::post('/verificacionInformes', [TutoresController::class, 'verificacionInformes']);
    Route::post('/obtenerDatosReporteSemestralIndividual', [TutoresController::class, 'obtenerDatosReporteSemestralIndividual']);
    Route::post('/cargarInformeSemestralIndividual', [TutoresController::class, 'cargarInformeSemestralIndividual']);


    Route::get('/tipos_beca', [TutoresController::class, 'tipos_beca']);
    Route::get('/tipos_actividad', [TutoresController::class, 'tipos_actividad']);
    Route::get('/tipos_modalidad', [TutoresController::class, 'tipos_modalidad']);
    Route::get('/tipos_situacion', [TutoresController::class, 'tipos_situacion']);
    Route::get('/tipos_canalizacion', [TutoresController::class, 'tipos_canalizacion']);
    Route::get('/tipos_canalizacion_becas', [TutoresController::class, 'tipos_canalizacion_becas']);
    Route::get('/tipos_logros', [TutoresController::class, 'tipos_logros']);

    Route::post('/verificarAlumnoReporteSemestral', [TutoresController::class, 'verificarAlumnoReporteSemestral']);

    



});


Route::get('/prueba', function (Request $request) {
    return 'HOLA ESTA ES UNA PRUEBA';
});


