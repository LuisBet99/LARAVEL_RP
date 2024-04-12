<?php

namespace App\Http\Controllers;

use App\Models\Docentes;
use App\Models\FechasPeriodos;
use App\Models\FechasPeriodosAsignadas;
use App\Models\Generaciones;
use App\Models\InformePeriodos;
use App\Models\ListadoTutorados;
use App\Models\PrimerInforme;
use App\Models\reporte_semestral_individual;
use App\Models\SegundoInforme;
use App\Models\TercerInforme;
use App\Models\tipos_actividad_tutorial;
use App\Models\tipos_beca;
use App\Models\tipos_canalizacion;
use App\Models\tipos_canalizacion_becas;
use App\Models\tipos_logros;
use App\Models\tipos_modalidad;
use App\Models\tipos_situacion_problematica;
use App\Models\Tutores;
use Illuminate\Http\Request;

class TutoresController extends Controller
{
    public function obtenerGeneracionesTutores()
    {

        try {
            //Obtener todas las generaciones NO ASIGNADAS
            $encontrar_generaciones = Generaciones::select('*')->where('estatus_asignada', 'SI')->where('tutores_asignados', 'SI')->get();
            if ($encontrar_generaciones->count() > 0) {
                return response()->json(['codigo' => 1, 'mensaje' => 'Generaciones obtenidas.', 'data' => $encontrar_generaciones], 200);
            } else {
                return response()->json(['codigo' => 1, 'mensaje' => 'No existen generaciones actualmente.', 'data' => $encontrar_generaciones], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 2, 'mensaje' => 'Ocurrio un error al obtener las generaciones: ' . $th], 500);
        }
    }
    public function obtenerListaTutorados(Request $request)
    {

        $id_generacion = $request->id_generacion;
        $id_periodo = $request->id_periodo; //PRIMERO, SEGUNDO,TERCERO, CUARTO
        $informe = $request->informe; // primer_informe , segundo_informe, tercer_informe
        $id_usuario = $request->id_tutor;


        $id_docente = Docentes::select('id')
            ->where('id_usuario', $id_usuario)
            ->get();

        //BUSCAMOS EL ID DEL TUTOR EN LA TABLA DE TUTORES, SEGUN EL ID DEL DOCENTE:
        $id_tutor = Tutores::select('id')
            ->where('id_docente', $id_docente[0]->id)
            ->get();

         $id_tutor= $id_tutor[0]->id;



        $periodo_numero = 0;
        if ($id_periodo == 'PRIMERO') {
            $periodo_numero = 1;
        }
        if ($id_periodo == 'SEGUNDO') {
            $periodo_numero = 2;
        }
        if ($id_periodo == 'TERCERO') {
            $periodo_numero = 3;
        }
        if ($id_periodo == 'CUARTO') {
            $periodo_numero = 4;
        }

        if ($id_periodo == 'PRIMERO') {

            $tabla_informe = 'primer_informe';
            $tabla_informe_periodo = 'id_primer_informe';
            $campo_inform_periodo = 'id_informe_periodo_01';

            if ($informe == 'primer_informe') {
                $tabla_informe = 'primer_informe';
                $tabla_informe_periodo = 'id_primer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_01';
            }
            if ($informe == 'segundo_informe') {
                $tabla_informe = 'segundo_informe';
                $tabla_informe_periodo = 'id_segundo_informe';
                // $campo_inform_periodo= 'id_informe_periodo_02';
            }
            if ($informe == 'tercer_informe') {
                $tabla_informe = 'tercer_informe';
                $tabla_informe_periodo = 'id_tercer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_03';
            }

            $listado_tutorados = ListadoTutorados::select('listado_tutorados.*', 'informe_periodos.*', $tabla_informe . '.id as ID_INFORME', $tabla_informe . '.estatus_informe AS ESTATUS_INFORME', 'alumnos.id', 'alumnos.nombre as NombreAlumno', 'alumnos.apellido_paterno as ApellidoPaternoAlumno', 'alumnos.apellido_materno as ApellidoMaternoAlumno', 'alumnos.numero_checador as NumeroControlAlumno')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->join('informe_periodos', 'informe_periodos.id', '=', 'listado_tutorados.' . $campo_inform_periodo)
                ->join($tabla_informe, $tabla_informe . '.id', '=', 'informe_periodos.' . $tabla_informe_periodo)
                ->where('listado_tutorados.id_tutor', $id_tutor)
                ->where('listado_tutorados.id_generacion', $id_generacion)
                ->where('listado_tutorados.estatus_tutorado', 'ACTIVO')
                ->get();


            // Ahora obtenemos las fechas asignadas segun el periodo y generacion , para las tres sesiones:
            $fechas_periodos_asignadas = FechasPeriodosAsignadas::select('fechas_periodos_asignadas.*')
                ->where('id_periodo', $periodo_numero)
                ->where('id_generacion', $id_generacion)
                ->get();


            $fecha_primera_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_primera_sesion)
                ->get();
            $fecha_segunda_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_segunda_sesion)
                ->get();
            $fecha_tercera_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_tercera_sesion)
                ->get();

            $lista_fechas = [
                'fecha_primera_sesion' => $fecha_primera_sesion,
                'fecha_segunda_sesion' => $fecha_segunda_sesion,
                'fecha_tercera_sesion' => $fecha_tercera_sesion,
            ];
        }

        if ($id_periodo == 'SEGUNDO') {
            $tabla_informe = 'primer_informe';
            $tabla_informe_periodo = 'id_primer_informe';
            $campo_inform_periodo = 'id_informe_periodo_02';

            if ($informe == 'primer_informe') {
                $tabla_informe = 'primer_informe';
                $tabla_informe_periodo = 'id_primer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_01';
            }
            if ($informe == 'segundo_informe') {
                $tabla_informe = 'segundo_informe';
                $tabla_informe_periodo = 'id_segundo_informe';
                // $campo_inform_periodo= 'id_informe_periodo_02';
            }
            if ($informe == 'tercer_informe') {
                $tabla_informe = 'tercer_informe';
                $tabla_informe_periodo = 'id_tercer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_03';
            }

            $listado_tutorados = ListadoTutorados::select('listado_tutorados.*', 'informe_periodos.*', $tabla_informe . '.id as ID_INFORME', $tabla_informe . '.estatus_informe AS ESTATUS_INFORME', 'alumnos.id', 'alumnos.nombre as NombreAlumno', 'alumnos.apellido_paterno as ApellidoPaternoAlumno', 'alumnos.apellido_materno as ApellidoMaternoAlumno', 'alumnos.numero_checador as NumeroControlAlumno')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->join('informe_periodos', 'informe_periodos.id', '=', 'listado_tutorados.' . $campo_inform_periodo)
                ->join($tabla_informe, $tabla_informe . '.id', '=', 'informe_periodos.' . $tabla_informe_periodo)
                ->where('listado_tutorados.id_tutor', $id_tutor)
                ->where('listado_tutorados.id_generacion', $id_generacion)
                ->where('listado_tutorados.estatus_tutorado', 'ACTIVO')
                ->get();
                 // Ahora obtenemos las fechas asignadas segun el periodo y generacion , para las tres sesiones:
            $fechas_periodos_asignadas = FechasPeriodosAsignadas::select('fechas_periodos_asignadas.*')
            ->where('id_periodo', $periodo_numero)
            ->where('id_generacion', $id_generacion)
            ->get();


        $fecha_primera_sesion = FechasPeriodos::select('fechas_periodos.*')
            ->where('id', $fechas_periodos_asignadas[0]->id_fecha_primera_sesion)
            ->get();
        $fecha_segunda_sesion = FechasPeriodos::select('fechas_periodos.*')
            ->where('id', $fechas_periodos_asignadas[0]->id_fecha_segunda_sesion)
            ->get();
        $fecha_tercera_sesion = FechasPeriodos::select('fechas_periodos.*')
            ->where('id', $fechas_periodos_asignadas[0]->id_fecha_tercera_sesion)
            ->get();

        $lista_fechas = [
            'fecha_primera_sesion' => $fecha_primera_sesion,
            'fecha_segunda_sesion' => $fecha_segunda_sesion,
            'fecha_tercera_sesion' => $fecha_tercera_sesion,
        ];
        }
        if ($id_periodo == 'TERCERO') {
            $tabla_informe = 'primer_informe';
            $tabla_informe_periodo = 'id_primer_informe';
            $campo_inform_periodo = 'id_informe_periodo_03';

            if ($informe == 'primer_informe') {
                $tabla_informe = 'primer_informe';
                $tabla_informe_periodo = 'id_primer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_01';
            }
            if ($informe == 'segundo_informe') {
                $tabla_informe = 'segundo_informe';
                $tabla_informe_periodo = 'id_segundo_informe';
                // $campo_inform_periodo= 'id_informe_periodo_02';
            }
            if ($informe == 'tercer_informe') {
                $tabla_informe = 'tercer_informe';
                $tabla_informe_periodo = 'id_tercer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_03';
            }

            $listado_tutorados = ListadoTutorados::select('listado_tutorados.*', 'informe_periodos.*', $tabla_informe . '.id as ID_INFORME', $tabla_informe . '.estatus_informe AS ESTATUS_INFORME', 'alumnos.id', 'alumnos.nombre as NombreAlumno', 'alumnos.apellido_paterno as ApellidoPaternoAlumno', 'alumnos.apellido_materno as ApellidoMaternoAlumno', 'alumnos.numero_checador as NumeroControlAlumno')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->join('informe_periodos', 'informe_periodos.id', '=', 'listado_tutorados.' . $campo_inform_periodo)
                ->join($tabla_informe, $tabla_informe . '.id', '=', 'informe_periodos.' . $tabla_informe_periodo)
                ->where('listado_tutorados.id_tutor', $id_tutor)
                ->where('listado_tutorados.id_generacion', $id_generacion)
                ->where('listado_tutorados.estatus_tutorado', 'ACTIVO')
                ->get();
            // Ahora obtenemos las fechas asignadas segun el periodo y generacion , para las tres sesiones:
            $fechas_periodos_asignadas = FechasPeriodosAsignadas::select('fechas_periodos_asignadas.*')
                ->where('id_periodo', $periodo_numero)
                ->where('id_generacion', $id_generacion)
                ->get();

            $fecha_primera_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_primera_sesion)
                ->get();
            $fecha_segunda_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_segunda_sesion)
                ->get();
            $fecha_tercera_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_tercera_sesion)
                ->get();

            $lista_fechas = [
                'fecha_primera_sesion' => $fecha_primera_sesion,
                'fecha_segunda_sesion' => $fecha_segunda_sesion,
                'fecha_tercera_sesion' => $fecha_tercera_sesion,
            ];
        }
        if ($id_periodo == 'CUARTO') {
            $tabla_informe = 'primer_informe';
            $tabla_informe_periodo = 'id_primer_informe';
            $campo_inform_periodo = 'id_informe_periodo_04';

            if ($informe == 'primer_informe') {
                $tabla_informe = 'primer_informe';
                $tabla_informe_periodo = 'id_primer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_01';
            }
            if ($informe == 'segundo_informe') {
                $tabla_informe = 'segundo_informe';
                $tabla_informe_periodo = 'id_segundo_informe';
                // $campo_inform_periodo= 'id_informe_periodo_02';
            }
            if ($informe == 'tercer_informe') {
                $tabla_informe = 'tercer_informe';
                $tabla_informe_periodo = 'id_tercer_informe';
                // $campo_inform_periodo= 'id_informe_periodo_03';
            }

            $listado_tutorados = ListadoTutorados::select('listado_tutorados.*', 'informe_periodos.*', $tabla_informe . '.id as ID_INFORME', $tabla_informe . '.estatus_informe AS ESTATUS_INFORME', 'alumnos.id', 'alumnos.nombre as NombreAlumno', 'alumnos.apellido_paterno as ApellidoPaternoAlumno', 'alumnos.apellido_materno as ApellidoMaternoAlumno', 'alumnos.numero_checador as NumeroControlAlumno')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->join('informe_periodos', 'informe_periodos.id', '=', 'listado_tutorados.' . $campo_inform_periodo)
                ->join($tabla_informe, $tabla_informe . '.id', '=', 'informe_periodos.' . $tabla_informe_periodo)
                ->where('listado_tutorados.id_tutor', $id_tutor)
                ->where('listado_tutorados.id_generacion', $id_generacion)
                ->where('listado_tutorados.estatus_tutorado', 'ACTIVO')
                ->get();
            // Ahora obtenemos las fechas asignadas segun el periodo y generacion , para las tres sesiones:
            $fechas_periodos_asignadas = FechasPeriodosAsignadas::select('fechas_periodos_asignadas.*')
                ->where('id_periodo', $periodo_numero)
                ->where('id_generacion', $id_generacion)
                ->get();

            $fecha_primera_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_primera_sesion)
                ->get();
            $fecha_segunda_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_segunda_sesion)
                ->get();
            $fecha_tercera_sesion = FechasPeriodos::select('fechas_periodos.*')
                ->where('id', $fechas_periodos_asignadas[0]->id_fecha_tercera_sesion)
                ->get();

            $lista_fechas = [
                'fecha_primera_sesion' => $fecha_primera_sesion,
                'fecha_segunda_sesion' => $fecha_segunda_sesion,
                'fecha_tercera_sesion' => $fecha_tercera_sesion,
            ];
        }

        return response()->json([
            'codigo' => 1,
            'mensaje' => 'Generaciones obtenidas.',
            'fechas' => $lista_fechas,
            'data' => $listado_tutorados,
            'data_recibir' => $request->all()
        ], 200);




        // return $informePeriodo;


    }



    public function capturarPrimerInforme(Request $request)
    {

        $data_completa = $request->all();

        //Data de alumno, generacion, tutor y periodo:
        $id_alumno = $request->input('id_alumno');
        $id_generacion = $request->input('id_generacion');
        $id_tutor = $request->input('id_tutor');
        $periodo = $request->input('periodo'); //PRIMERO, SEGUNDO, TERCERO, CUARTO
        $informe = $request->input('informe'); //primer_informe, segundo_informe, tercer_informe
        $id_primer_informe = $request->input('id_primer_informe');

        // Data del informe:
        $estatus_atendido = $request->input('primera'); //ASISTIO = SI, NO
        $registro_diagnostico = $request->input('segunda'); //FICHA REGISTRO = SI, NO
        $beca = $request->input('tercera'); //BECA = SI, NO
        $tipo_beca = $request->input('cuarta'); // TIPO BECA = PROM,SOCI,DEP,OTRO
        $numero_sesiones = $request->input('numero_sesiones');
        $horas_atencion = $request->input('horas_atencion');
        //Convertir en string el porcentaje de asistencias.
        $porcentaje_asistencias = $request->input('asistencia');
        $actividad_tutorial = $request->input('quinta'); //ACTIVIDAD TUTORIAL = CANALIZACION,ASESORIA, ETC
        $modalidad = $request->input('sexta'); //MODALIDAD = GRUPAL,INDIVIDUAL,AMBAS
        $situacion_problematica = $request->input('septima'); //SITUACION PROBLEMATICA = INTEGRACION,SOCIAL
        $canalizacion = $request->input('octava'); // CANALIZACION = SI,NO
        $tipo_canalizacion = $request->input('decima'); // CANALIZACION = SI,NO
        $beca_canalizacion = $request->input('onceava'); // CANALIZACION = SI,NO
        $canalizacion_atendida = $request->input('doceava'); // CANALIZACION = SI,NO
        $observaciones = $request->input('novena'); //OBSERVACIONES
        $estatus_informe = 1;


        //Actualizamos el primer informe:
        $primer_informe = PrimerInforme::find($id_primer_informe);
        if ($primer_informe->estatus_informe === 1) {
            return response()->json([
                'codigo' => 2,
                'mensaje' => 'Primer informe YA CAPTURADO.',
                'data' => $primer_informe,
                'data_recibida' => $request->all()
            ], 200);
        }
        if ($primer_informe->estatus_informe !== 1) {
            $primer_informe->estatus_atendido = $estatus_atendido;
            $primer_informe->registro_diagnostico = $registro_diagnostico;
            $primer_informe->beca = $beca;
            $primer_informe->tipo_beca = $tipo_beca;
            $primer_informe->numero_sesiones = $numero_sesiones;
            $primer_informe->horas_atencion = $horas_atencion;
            $primer_informe->porcentaje_asistencias = strval($porcentaje_asistencias);
            $primer_informe->actividad_tutorial = $actividad_tutorial;
            $primer_informe->modalidad = $modalidad;
            $primer_informe->situacion_problematica = $situacion_problematica;
            $primer_informe->canalizacion = $canalizacion;
            $primer_informe->tipo_canalizacion = $tipo_canalizacion;
            $primer_informe->beca_canalizacion = $beca_canalizacion;
            $primer_informe->canalizacion_atendida = $canalizacion_atendida;
            $primer_informe->observaciones = $observaciones;
            $primer_informe->estatus_informe = $estatus_informe;
            $primer_informe->save();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'Primer informe capturado.',
                'data' => $primer_informe,
                'data_recibida' => $request->all()
            ], 200);
        }
    }
    public function capturarSegundoInforme(Request $request)
    {

        $estatus_atendido = $request->input('primera'); //ASISTIO = SI, NO
        $numero_sesiones = $request->input('segunda'); //NUMERO DE SESIONES
        $horas_atencion = $request->input('tercera'); //HORAS DE ATENCION
        $porcentaje_asistencias = $request->input('asistencia');
        $actividad_tutorial = $request->input('cuarta'); // ACTIVIDAD TUTORIAL = CANALIZACION,ASESORIA, ETC
        $modalidad = $request->input('quinta'); //MODALIDAD = GRUPAL,INDIVIDUAL,AMBAS
        $situacion_problematica = $request->input('sexta'); //SITUACION PROBLEMATICA = INTEGRACION,SOCIAL
        $canalizacion = $request->input('septima'); //CANALIZACION = SI,NO
        $observaciones = $request->input('novena'); //OBSERVACIONES
        $tipo_canalizacion = $request->input('decima'); // TIPO DE CANALIZACION
        $beca_canalizacion = $request->input('onceava'); // BECA DE CANALIZACION
        $canalizacion_atendida = $request->input('doceava'); // ATENCION DE CANALIZACION
        $registro_diagnostico = "N/A";
        $beca = "N/A";
        $tipo_beca = "N/A";
        $estatus_informe = 1;

        $id_alumno = $request->input('id_alumno');
        $id_generacion = $request->input('id_generacion');
        $id_tutor = $request->input('id_tutor');
        $periodo = $request->input('periodo'); //PRIMERO, SEGUNDO, TERCERO, CUARTO
        $informe = $request->input('informe'); //primer_informe, segundo_informe, tercer_informe
        $id_segundo_informe = $request->input('id_segundo_informe');


        $segundo_informe = SegundoInforme::find($id_segundo_informe);
        if ($segundo_informe->estatus_informe === 1) {
            return response()->json([
                'codigo' => 2,
                'mensaje' => 'Primer informe YA CAPTURADO.',
                'data' => $segundo_informe,
                'data_recibida' => $request->all()
            ], 200);
        }
        if ($segundo_informe->estatus_informe !== 1) {
            $segundo_informe->estatus_atendido = $estatus_atendido;
            $segundo_informe->registro_diagnostico = $registro_diagnostico;
            $segundo_informe->beca = $beca;
            $segundo_informe->tipo_beca = $tipo_beca;
            $segundo_informe->numero_sesiones = $numero_sesiones;
            $segundo_informe->horas_atencion = $horas_atencion;
            $segundo_informe->porcentaje_asistencias = strval($porcentaje_asistencias);
            $segundo_informe->actividad_tutorial = $actividad_tutorial;
            $segundo_informe->modalidad = $modalidad;
            $segundo_informe->situacion_problematica = $situacion_problematica;
            $segundo_informe->canalizacion = $canalizacion;
            $segundo_informe->tipo_canalizacion = $tipo_canalizacion;
            $segundo_informe->beca_canalizacion = $beca_canalizacion;
            $segundo_informe->canalizacion_atendida = $canalizacion_atendida;
            $segundo_informe->observaciones = $observaciones;
            $segundo_informe->estatus_informe = $estatus_informe;
            $segundo_informe->save();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'Primer informe capturado.',
                'data' => $segundo_informe,
                'data_recibida' => $request->all()
            ], 200);
        }
    }
    public function capturarTercerInforme(Request $request)
    {

        $estatus_atendido = $request->input('primera'); //ASISTIO = SI, NO
        $numero_sesiones = $request->input('segunda'); //NUMERO DE SESIONES
        $horas_atencion = $request->input('tercera'); //HORAS DE ATENCION
        $porcentaje_asistencias = $request->input('asistencia');
        $actividad_tutorial = $request->input('cuarta'); // ACTIVIDAD TUTORIAL = CANALIZACION,ASESORIA, ETC
        $modalidad = $request->input('quinta'); //MODALIDAD = GRUPAL,INDIVIDUAL,AMBAS
        $situacion_problematica = $request->input('sexta'); //SITUACION PROBLEMATICA = INTEGRACION,SOCIAL
        $canalizacion = $request->input('septima'); //CANALIZACION = SI,NO
        $observaciones = $request->input('novena'); //OBSERVACIONES
        $tipo_canalizacion = $request->input('decima'); // TIPO DE CANALIZACION
        $beca_canalizacion = $request->input('onceava'); // BECA DE CANALIZACION
        $canalizacion_atendida = $request->input('doceava'); // ATENCION DE CANALIZACION
        $registro_diagnostico = "N/A";
        $beca = "N/A";
        $tipo_beca = "N/A";
        $estatus_informe = 1;

        $id_tercer_informe = $request->input('id_tercer_informe');
        $id_alumno = $request->input('id_alumno');
        $id_generacion = $request->input('id_generacion');
        $id_tutor = $request->input('id_tutor');
        $periodo = $request->input('periodo'); //PRIMERO, SEGUNDO, TERCERO, CUARTO
        $informe = $request->input('informe'); //primer_informe, segundo_informe, tercer_informe


        $tercer_informe = TercerInforme::find($id_tercer_informe);
        if ($tercer_informe->estatus_informe === 1) {
            return response()->json([
                'codigo' => 2,
                'mensaje' => 'Primer informe YA CAPTURADO.',
                'data' => $tercer_informe,
                'data_recibida' => $request->all()
            ], 200);
        }
        if ($tercer_informe->estatus_informe !== 1) {
            $tercer_informe->estatus_atendido = $estatus_atendido;
            $tercer_informe->registro_diagnostico = $registro_diagnostico;
            $tercer_informe->beca = $beca;
            $tercer_informe->tipo_beca = $tipo_beca;
            $tercer_informe->numero_sesiones = $numero_sesiones;
            $tercer_informe->horas_atencion = $horas_atencion;
            $tercer_informe->porcentaje_asistencias = strval($porcentaje_asistencias);
            $tercer_informe->actividad_tutorial = $actividad_tutorial;
            $tercer_informe->modalidad = $modalidad;
            $tercer_informe->situacion_problematica = $situacion_problematica;
            $tercer_informe->canalizacion = $canalizacion;
            $tercer_informe->tipo_canalizacion = $tipo_canalizacion;
            $tercer_informe->beca_canalizacion = $beca_canalizacion;
            $tercer_informe->canalizacion_atendida = $canalizacion_atendida;
            $tercer_informe->observaciones = $observaciones;
            $tercer_informe->estatus_informe = $estatus_informe;
            $tercer_informe->save();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'Primer informe capturado.',
                'data' => $tercer_informe,
                'data_recibida' => $request->all()
            ], 200);
        }
    }

    public function verificacionInformes(Request $request)
    {
        $id_usuario = $request->input('id_tutor');
        $id_generacion = $request->input('id_generacion');
        $periodo = $request->input('periodo');

        $total_tutorados = 0;

        //SEGUN EL ID DEL USUARIO, LO BUSCAMOS EN LA TABLA DE TUTORES:
        $id_docente = Docentes::select('id')
            ->where('id_usuario', $id_usuario)
            ->get();

        //BUSCAMOS EL ID DEL TUTOR EN LA TABLA DE TUTORES, SEGUN EL ID DEL DOCENTE:
        $id_tutor = Tutores::select('id')
            ->where('id_docente', $id_docente[0]->id)
            ->get();



        $id_tutor= $id_tutor[0]->id;

        try {

            //ES NECESARIO BUSCAR LOS INFORMES QUE ESTEN EN ESTATUS 1, ES DECIR, QUE ESTEN CAPTURADOS.
            $tutorados_por_tutor = ListadoTutorados::select('*')
                ->where('id_generacion', $id_generacion)
                ->where('id_tutor', $id_tutor)
                ->get();

            //CONTAMOS EL NUMERO TOTAL DE TUTORADOS QUE TIENE EL TUTOR:
            $total_tutorados = count($tutorados_por_tutor); //2


            //TENEMOS QUE TENER SEGUN EL NUMERO DE TUTORADOS EN TOTAL, POR CADA INFORME (PRIMERO,SEGUNDO,TERCERO) QUE ESTEN YA CAPTURADOS TODOS.
            $primer_informe = PrimerInforme::select('*')
                ->where('id_generacion', $id_generacion)
                ->where('estatus_informe', 1)
                ->where('periodo', $periodo)
                ->get(); //2

            $total_primer_informe_terminado = count($primer_informe);

            $segundo_informe = SegundoInforme::select('*')
                ->where('id_generacion', $id_generacion)
                ->where('estatus_informe', 1)
                ->where('periodo', $periodo)

                ->get(); //2

            $total_segundo_informe_terminado = count($segundo_informe);

            $tercer_informe = TercerInforme::select('*')
                ->where('id_generacion', $id_generacion)
                ->where('estatus_informe', 1)
                ->where('periodo', $periodo)
                ->get(); //2

            $total_tercer_informe_terminado = count($tercer_informe);


            $total_informes_terminados = $total_primer_informe_terminado + $total_segundo_informe_terminado + $total_tercer_informe_terminado;


            $pendientes_primer_informe =$total_primer_informe_terminado-$total_tutorados;
            $pendientes_segundo_informe =$total_segundo_informe_terminado-$total_tutorados;
            $pendientes_tercer_informe =$total_tercer_informe_terminado-$total_tutorados;

            $pendientes_totales = $pendientes_primer_informe + $pendientes_segundo_informe  + $pendientes_tercer_informe;


            return response()->json([
                'codigo' => 1,
                'mensaje' => 'INFORMACION DE INFORMES',
                'PRIMERO' => [
                    'total_primer_informe_terminado' => $total_primer_informe_terminado,
                    'total_tutorados' => $total_tutorados,
                    'pendientes' => $total_primer_informe_terminado-$total_tutorados,
                    'estatus' => $total_primer_informe_terminado >= $total_tutorados ? 1 : 0,
                ],
                'SEGUNDO' => [
                    'total_segundo_informe_terminado' => $total_segundo_informe_terminado,
                    'total_tutorados' => $total_tutorados,
                    'pendientes' =>$total_segundo_informe_terminado- $total_tutorados ,
                    'estatus' => $total_segundo_informe_terminado >= $total_tutorados ? 1 : 0,
                ],
                'TERCERO' => [
                    'total_tercer_informe_terminado' => $total_tercer_informe_terminado,
                    'total_tutorados' => $total_tutorados,
                    'pendientes' =>$total_tercer_informe_terminado- $total_tutorados,
                    'estatus' => $total_tercer_informe_terminado >= $total_tutorados ? 1 : 0,
                ],
                'TOTAL_INFORMES_TERMINADOS' => $total_informes_terminados,
                'PENDIENTES_TOTALES' =>  $pendientes_totales ,
                'ESTATUS' =>  $pendientes_totales  >= 0 ? 0 : 1,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function cargarInformeSemestralIndividual(Request $request){

        //Recibimos los datos y agregamps el informe semestral:
        $id_alumno = $request->input('id_alumno');
        $id_tutor = $request->input('id_tutor');
        $id_generacion = $request->input('id_generacion');
        $periodo = $request->input('periodo');
        $fecha = $request->input('fecha');
        $numero_sesiones_totales = $request->input('segunda');
        $numero_horas_totales = $request->input('tercera');
        $numero_total_asistencias = $request->input('cuarta');
        $clave_prep_actividad = $request->input('quinta');
        $clave_prep_situacion = $request->input('sexta');
        $clave_prep_logros = $request->input('septima');
        $observaciones = $request->input('octava');

        $totalNumeroSesiones = $request->input('totalNumeroSesiones');
        $porcentajeTotalNumeroAsistencias = $request->input('porcentajeTotalNumeroAsistencias');
        $totalNumeroHorasAtencion = $request->input('totalNumeroHorasAtencion');
        $totalNumeroCanalizaciones = $request->input('totalNumeroCanalizaciones');
        $actividadTutorialPrep = $request->input('actividadTutorialPrep');
        $modalidadPrep = $request->input('modalidadPrep');
        $situacionProblematicaPrep = $request->input('situacionProblematicaPrep');
        $tipoCanalizacionPrep = $request->input('tipoCanalizacionPrep');
        $becaCanalizacionPrep = $request->input('becaCanalizacionPrep');
        $canalizacionAtendidaPrep = $request->input('canalizacionAtendidaPrep');
        $fecha_ultima_modificacion = $request->input('fecha_ultima_modificacion');

        //Ahora verificamos que en la tabla de informes semestrales no exista un registro con el mismo alumno, tutor, generacion y periodo:
        $reporte_semestral_individual = reporte_semestral_individual::select('*')
            ->where('id_alumno', $id_alumno)
            ->where('id_tutor', $id_tutor)
            ->where('id_generacion', $id_generacion)
            ->where('periodo', $periodo)
            ->get();

        if (count($reporte_semestral_individual) > 0) {
            return response()->json([
                'codigo' => 2,
                'mensaje' => 'Ya existe un reporte semestral individual para este alumno.',
                'data' => $reporte_semestral_individual,
            ], 200);
        }else{
            $insertar_reporte_semestral = reporte_semestral_individual:: create([
                'numero_sesiones_totales' => $numero_sesiones_totales,
                'numero_horas_totales' => $numero_horas_totales,
                'numero_total_asistencias' => $numero_total_asistencias,
                'clave_prep_actividad' => $clave_prep_actividad,
                'clave_prep_situacion' => $clave_prep_situacion,
                'clave_prep_logros' => $clave_prep_logros,
                'observaciones' => $observaciones,
                'totalNumeroSesiones' => $totalNumeroSesiones,
                'porcentajeTotalNumeroAsistencias' => $porcentajeTotalNumeroAsistencias,
                'totalNumeroHorasAtencion' => $totalNumeroHorasAtencion,
                'totalNumeroCanalizaciones' => $totalNumeroCanalizaciones,
                'actividadTutorialPrep' => $actividadTutorialPrep,
                'modalidadPrep' => $modalidadPrep,
                'situacionProblematicaPrep' => $situacionProblematicaPrep,
                'tipoCanalizacionPrep' => $tipoCanalizacionPrep,
                'becaCanalizacionPrep' => $becaCanalizacionPrep,
                'canalizacionAtendidaPrep' => $canalizacionAtendidaPrep,
                'fecha' => $fecha,
                'id_alumno' => $id_alumno,
                'id_tutor' => $id_tutor,
                'periodo' => $periodo,
                'id_generacion' => $id_generacion,
                'mostrar' => 1,
            ]);

            //Validamos que si se inserto:
            if ($insertar_reporte_semestral) {
                return response()->json([
                    'codigo' => 1,
                    'mensaje' => 'Reporte semestral individual insertado.',
                    'data' => $insertar_reporte_semestral,
                ], 200);
            }else{
                return response()->json([
                    'codigo' => 2,
                    'mensaje' => 'Error al insertar reporte semestral individual.',
                    'data' => $insertar_reporte_semestral,
                ], 500);
            }
        }





    }

    public function verificarAlumnoReporteSemestral(Request $request){
        $id_alumno = $request->input('id_alumno');
        $id_usuario = $request->input('id_tutor');

          //SEGUN EL ID DEL USUARIO, LO BUSCAMOS EN LA TABLA DE TUTORES:
        $id_docente = Docentes::select('id')
          ->where('id_usuario', $id_usuario)
          ->get();

            //BUSCAMOS EL ID DEL TUTOR EN LA TABLA DE TUTORES, SEGUN EL ID DEL DOCENTE:
        $id_tutor = Tutores::select('id')
                ->where('id_docente', $id_docente[0]->id)
                ->get();



         $id_tutor= $id_tutor[0]->id;

        $id_generacion = $request->input('id_generacion');
        $periodo = $request->input('periodo');

        $reporte_semestral_individual = reporte_semestral_individual::select('*')
            ->where('id_alumno', $id_alumno)
            ->where('id_tutor', $id_tutor)
            ->where('id_generacion', $id_generacion)
            ->where('periodo', $periodo)
            ->get();


        if (count($reporte_semestral_individual) > 0) {
            return response()->json([
                'codigo' => 1,
                'mensaje' => 'Ya existe un reporte semestral individual CAPTURADO para este alumno.',
                'data' => $reporte_semestral_individual,
            ], 200);
        }else{
            return response()->json([
                'codigo' => 2,
                'mensaje' => 'No existe un reporte semestral individual para este alumno capturado.',
                'data' => $reporte_semestral_individual,
            ], 200);
        }
    }
    public function obtenerDatosReporteSemestralIndividual(Request $request)
    {
        try {

            $id_alumno = $request->input('id_alumno'); //7
            $id_generacion = $request->input('id_generacion'); //1
            $id_periodo = $request->input('periodo'); //1



            //Obtenemos del primero al tercer informe el numero total de sesiones:
            $totalNumeroSesionesPrimero = PrimerInforme::select('numero_sesiones')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('numero_sesiones');
            $totalNumeroSesionesSegundo = SegundoInforme::select('numero_sesiones')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('numero_sesiones');
            $totalNumeroSesionesTercero = TercerInforme::select('numero_sesiones')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('numero_sesiones');
            $totalNumeroSesiones = $totalNumeroSesionesPrimero + $totalNumeroSesionesSegundo + $totalNumeroSesionesTercero;

            //Obtenemos el total de diagnosticos:
            $totalNumeroDiagnosticosPrimero = PrimerInforme::select('registro_diagnostico')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)
                ->get();
            // $totalNumeroDiagnosticos= count(array_keys($totalNumeroDiagnosticosPrimero, 'SI'));

            $totalNumeroDiagnosticosPrimero = $totalNumeroDiagnosticosPrimero->filter(function ($item) {
                return $item->registro_diagnostico == 'SI'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
            });

            $totalNumeroDiagnosticos = $totalNumeroDiagnosticosPrimero->count();


            //Obtenemos el numero total de asistencias:
            $totalNumeroAsistenciasPrimero = PrimerInforme::select('porcentaje_asistencias')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('porcentaje_asistencias');
            $totalNumeroAsistenciasSegundo = SegundoInforme::select('porcentaje_asistencias')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('porcentaje_asistencias');
            $totalNumeroAsistenciasTercero = TercerInforme::select('porcentaje_asistencias')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('porcentaje_asistencias');
            $porcentajeTotalNumeroAsistencias = $totalNumeroAsistenciasPrimero + $totalNumeroAsistenciasSegundo + $totalNumeroAsistenciasTercero;


            //Obtenemos el numero total de horas de atencion:
            $totalNumeroHorasAtencionPrimero = PrimerInforme::select('horas_atencion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('horas_atencion');
            $totalNumeroHorasAtencionSegundo = SegundoInforme::select('horas_atencion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('horas_atencion');
            $totalNumeroHorasAtencionTercero = TercerInforme::select('horas_atencion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->sum('horas_atencion');
            $totalNumeroHorasAtencion = $totalNumeroHorasAtencionPrimero + $totalNumeroHorasAtencionSegundo + $totalNumeroHorasAtencionTercero;


            //Obtenemos el numero total de canalizaciones:
            $totalNumeroCanalizacionesPrimero = PrimerInforme::select('canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $totalNumeroCanalizacionesSegundo = SegundoInforme::select('canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $totalNumeroCanalizacionesTercero = TercerInforme::select('canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $totalNumeroCanalizaciones = [$totalNumeroCanalizacionesPrimero[0]->canalizacion, $totalNumeroCanalizacionesSegundo[0]->canalizacion, $totalNumeroCanalizacionesTercero[0]->canalizacion];
            $totalNumeroCanalizaciones = count(array_keys($totalNumeroCanalizaciones, 'SI')); //Contamos el numero de canalizaciones que sean 'SI':

            //Obtenemos las actividades tutoriales:
            $actividadPrimero = PrimerInforme::select('actividad_tutorial')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $actividadSegundo = SegundoInforme::select('actividad_tutorial')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $actividadTercero = TercerInforme::select('actividad_tutorial')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            //Guardamos en un array como el "totalNumeroCanalizaciones"
            $actividadTotal = [$actividadPrimero[0]->actividad_tutorial, $actividadSegundo[0]->actividad_tutorial, $actividadTercero[0]->actividad_tutorial];
            //Validamos cual clave se repite mas veces:
            $actividadTotal = array_count_values($actividadTotal);
            //Elegimos la que tenga mas:
            $actividadTotal = array_search(max($actividadTotal), $actividadTotal);


            //Ahora obtenemos las modalidades de la misma forma:
            $modalidadPrimero = PrimerInforme::select('modalidad')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $modalidadSegundo = SegundoInforme::select('modalidad')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $modalidadTercero = TercerInforme::select('modalidad')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            //Guardamos en un array como el "totalNumeroCanalizaciones"
            $modalidadTotal = [$modalidadPrimero[0]->modalidad, $modalidadSegundo[0]->modalidad, $modalidadTercero[0]->modalidad];
            //Validamos cual clave se repite mas veces:
            $modalidadTotal = array_count_values($modalidadTotal);
            //Elegimos la que tenga mas:
            $modalidadTotal = array_search(max($modalidadTotal), $modalidadTotal);

            //Ahora obtenemos las situaciones problematicas de la misma forma:
            $situacionPrimero = PrimerInforme::select('situacion_problematica')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $situacionSegundo = SegundoInforme::select('situacion_problematica')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $situacionTercero = TercerInforme::select('situacion_problematica')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            //Guardamos en un array como el "totalNumeroCanalizaciones"
            $situacionTotal = [$situacionPrimero[0]->situacion_problematica, $situacionSegundo[0]->situacion_problematica, $situacionTercero[0]->situacion_problematica];
            //Validamos cual clave se repite mas veces:
            $situacionTotal = array_count_values($situacionTotal);
            //Elegimos la que tenga mas:
            $situacionTotal = array_search(max($situacionTotal), $situacionTotal);

            //Ahora obtenemos los tipos_canalizacion de la misma forma:
            $canalizacionPrimero = PrimerInforme::select('tipo_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $canalizacionSegundo = SegundoInforme::select('tipo_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $canalizacionTercero = TercerInforme::select('tipo_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            //Guardamos en un array como el "totalNumeroCanalizaciones"
            $canalizacionTotal = [$canalizacionPrimero[0]->tipo_canalizacion, $canalizacionSegundo[0]->tipo_canalizacion, $canalizacionTercero[0]->tipo_canalizacion];
            //Validamos cual clave se repite mas veces:
            $canalizacionTotal = array_count_values($canalizacionTotal);
            //Elegimos la que tenga mas:
            $canalizacionTotal = array_search(max($canalizacionTotal), $canalizacionTotal);


            //Ahora hacemos lo mismo con la beca_canalizacion:
            $beca_canalizacionPrimero = PrimerInforme::select('beca_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $beca_canalizacionSegundo = SegundoInforme::select('beca_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $beca_canalizacionTercero = TercerInforme::select('beca_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();

            //Guardamos en un array como el "totalNumeroCanalizaciones"
            $beca_canalizacionTotal = [$beca_canalizacionPrimero[0]->beca_canalizacion, $beca_canalizacionSegundo[0]->beca_canalizacion, $beca_canalizacionTercero[0]->beca_canalizacion];
            //Validamos cual clave se repite mas veces:
            $beca_canalizacionTotal = array_count_values($beca_canalizacionTotal);
            //Elegimos la que tenga mas:
            $beca_canalizacionTotal = array_search(max($beca_canalizacionTotal), $beca_canalizacionTotal);


            //Ahora hacemos lo mismo con la canalizacion_atendida:
            $canalizacion_atendidaPrimero = PrimerInforme::select('canalizacion_atendida')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $canalizacion_atendidaSegundo = SegundoInforme::select('canalizacion_atendida')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();
            $canalizacion_atendidaTercero = TercerInforme::select('canalizacion_atendida')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $id_periodo)->get();

            //Guardamos en un array como el "totalNumeroCanalizaciones"
            $canalizacion_atendidaTotal = [$canalizacion_atendidaPrimero[0]->canalizacion_atendida, $canalizacion_atendidaSegundo[0]->canalizacion_atendida, $canalizacion_atendidaTercero[0]->canalizacion_atendida];
            //Validamos cual clave se repite mas veces:
            $canalizacion_atendidaTotal = array_count_values($canalizacion_atendidaTotal);
            //Elegimos la que tenga mas:
            $canalizacion_atendidaTotal = array_search(max($canalizacion_atendidaTotal), $canalizacion_atendidaTotal);









            $data = [
                'totalNumeroSesiones' => $totalNumeroSesiones,
                'totalNumeroDiagnosticos'=>$totalNumeroDiagnosticos,
                'porcentajeTotalNumeroAsistencias' => $porcentajeTotalNumeroAsistencias,
                'totalNumeroHorasAtencion' => $totalNumeroHorasAtencion,
                'totalNumeroCanalizaciones' => $totalNumeroCanalizaciones,
                'actividadTutorialPrep' => $actividadTotal,
                'modalidadPrep' => $modalidadTotal,
                'situacionProblematicaPrep' => $situacionTotal,
                'tipoCanalizacionPrep' => $canalizacionTotal,
                'becaCanalizacionPrep' => $beca_canalizacionTotal,
                'canalizacionAtendidaPrep' => $canalizacion_atendidaTotal

                // 'canalizaciones'=> [
                //     'totalNumeroCanalizacionesPrimero' => $totalNumeroCanalizacionesPrimero,
                //     'totalNumeroCanalizacionesSegundo' => $totalNumeroCanalizacionesSegundo,
                //     'totalNumeroCanalizacionesTercero' => $totalNumeroCanalizacionesTercero,
                // ]
            ];

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'Datos Reporte semestral.',
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_beca()
    {
        //
        try {
            $tipos_beca = tipos_beca::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'tipos_beca.',
                'data' => $tipos_beca,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_actividad()
    {
        try {
            $tipos_actividad = tipos_actividad_tutorial::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'tipos_actividad.',
                'data' => $tipos_actividad,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_modalidad()
    {

        try {
            $tipos_modalidad = tipos_modalidad::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'tipos_modalidad',
                'data' => $tipos_modalidad,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_situacion()
    {

        try {
            $tipos_situacion = tipos_situacion_problematica::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'Primer informe capturado.',
                'data' => $tipos_situacion,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_canalizacion()
    {
        try {
            $tipos_canalizacion = tipos_canalizacion::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'tipos_canalizacion.',
                'data' => $tipos_canalizacion,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_canalizacion_becas()
    {
        try {
            $tipos_canalizacion_becas = tipos_canalizacion_becas::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'tipos_canalizacion_becas.',
                'data' => $tipos_canalizacion_becas,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function tipos_logros()
    {
        try {
            $tipos_logros = tipos_logros::select('*')->get();

            return response()->json([
                'codigo' => 1,
                'mensaje' => 'tipos_logros.',
                'data' => $tipos_logros,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
