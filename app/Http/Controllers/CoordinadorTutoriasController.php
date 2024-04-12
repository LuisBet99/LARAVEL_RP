<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\Docentes;
use App\Models\FechasPeriodosAsignadas;
use App\Models\Generaciones;
use App\Models\InformePeriodos;
use App\Models\ListadoTutorados;
use App\Models\PrimerInforme;
use App\Models\SegundoInforme;
use App\Models\TercerInforme;
use Illuminate\Http\Request;

class CoordinadorTutoriasController extends Controller
{

    public function obtenerGeneracionesAlumnosAsignados(){
        try {
            //Obtener todas las generaciones NO ASIGNADAS
            $encontrar_generaciones = Generaciones::select('*')->where('estatus_asignada', 'SI')->where('tutores_asignados', 'NO')->get();
            if ($encontrar_generaciones->count() > 0) {
                return response()->json(['codigo' => 1, 'mensaje' => 'Generaciones obtenidas.', 'data' => $encontrar_generaciones], 200);
            } else {
                return response()->json(['codigo' => 2, 'mensaje' => 'No existen generaciones actualmente.', 'data' => $encontrar_generaciones], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 2, 'mensaje' => 'Ocurrio un error al obtener las generaciones: ' . $th], 500);
        }

    }
    public function obtenerAlumnosGeneraciones(Request $request){


        try {
            //Obtener todas las generaciones NO ASIGNADAS
            $alumnos = Alumnos::select('*')
            ->where('id_generacion', $request->id_generacion)
            ->where('id_carrera',$request->id_carrera)
            ->get();
            if ($alumnos->count() > 0) {
                $docentes = Docentes::select('docentes.*', 'tutores.id as tutor_id',
                'tutores.id_docente as tutor_id_docente')
                ->where('id_carrera',$request->id_carrera)
                ->join('tutores', 'tutores.id_docente', '=', 'docentes.id')
                ->distinct()
                ->get();
                if ($docentes->count() > 0) {
                    return response()->json(['codigo' => 1, 'mensaje' => 'ALUMNOS obtenidos.', 'data' => $alumnos, 'data2'=> $docentes], 200);
                }else{
                    return response()->json(['codigo' => 2, 'mensaje' => 'No existen DOCENTES actualmente.', 'data' => $alumnos], 200);
                }
            } else {
                return response()->json(['codigo' => 3, 'mensaje' => 'No existen ALUMNOS actualmente.', 'data' => $alumnos], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 4, 'mensaje' => 'Ocurrio un error al obtener los alumnos: ' . $th], 500);
        }

    }

    public function asignacionAlumnosTutorados(Request $request){

        //ACTUALIZAMOS LA GENERACION PARA YA NO VOLVERLA A MOSTRAR EN LA LISTA DE ASIGNACIONES.
        $data = $request->all(); // Esto obtiene los datos del JSON en forma de array de objetos
        $primer_informe='';
        $segundo_informe='';
        $tercer_informe='';
        $id_generacion='';
        foreach ($data as $item) {



            //Aqui vamos a recorrer el array de objetos:

            $id_docente = $item['id_docente'];
            $id_tutor = $item['id_tutor'];
            $id_generacion = $item['id_generacion'];

            $fechas_periodos_asignadas = FechasPeriodosAsignadas::select('*')->where('id_generacion',$id_generacion)->get();

            if($fechas_periodos_asignadas->count() <= 0){
                return response()->json(['codigo' => 2, 'mensaje' => 'No existen fechas asignadas para esta generacion.'], 200);
            }

            //Este es el array de id's de alumnos:
            $id_alumnos = $item['id_alumnos'];

            $id_informe1=0;
            $id_informe2=0;
            $id_informe3=0;
            $id_informe4=0;

            //Por cada docente, recorremos los id's de los alumnos asignados:
            foreach ($id_alumnos as $id_alumno) {

                //Necesitamos crear los 3 informes por los 4 periodos, para dar un total de 12 informes:
                    for ($i=0; $i <= 3 ; $i++) {

                        $primer_informe =  PrimerInforme::create([
                            'observaciones'=>'NA',
                            'id_generacion' => $id_generacion,
                            'id_alumno' => $id_alumno,
                            'periodo' => $i+1,

                        ]);
                        $segundo_informe =  SegundoInforme::create([
                            'observaciones'=>'NA',
                            'id_generacion' => $id_generacion,
                            'id_alumno' => $id_alumno,
                            'periodo' => $i+1,
                        ]);
                        $tercer_informe =  TercerInforme::create([
                            'observaciones'=>'NA',
                            'id_generacion' => $id_generacion,
                            'id_alumno' => $id_alumno,
                            'periodo' => $i+1,
                        ]);


                        $informe_periodo = InformePeriodos::create(
                            [
                                'id_fechas_periodos_asignadas' => $fechas_periodos_asignadas[$i]->id,
                                'id_primer_informe' => $primer_informe->id,
                                'id_segundo_informe' => $segundo_informe->id,
                                'id_tercer_informe' => $tercer_informe->id,
                                'id_generacion' => $id_generacion,
                                'mostrar'=> 1

                            ]
                            );

                         if($i == 0){ $id_informe1 = $informe_periodo->id;}
                         if($i == 1){ $id_informe2 = $informe_periodo->id;}
                         if($i == 2){ $id_informe3 = $informe_periodo->id;}
                         if($i == 3){ $id_informe4 = $informe_periodo->id;}

                    }



                //Por cada alumno, impactamos en la tabla "LISTADO TUTORADOS"
                $insert_listado_tutorados = ListadoTutorados::create(
                    [
                        'estatus_tutorado' => 'ACTIVO',
                        'id_alumno' => $id_alumno,
                        'id_tutor' => $id_tutor,
                        'id_generacion' => $id_generacion,
                        'id_informe_periodo_01' => $id_informe1,
                        'id_informe_periodo_02' => $id_informe2,
                        'id_informe_periodo_03' => $id_informe3,
                        'id_informe_periodo_04' => $id_informe4,
                        'mostrar' => 1
                    ]
                );

                if($insert_listado_tutorados){
                    continue;
                }

            }
        }

        //Cuando termine actualizamos las generaciones para que ya no le aparezcan al COORDINADOR Y NO VUELVA ASIGNAR TUTORES:
        $actualizar_generacion = Generaciones::where('id',$id_generacion)->update([
            'tutores_asignados'=> 'SI'
        ]);


        return response()->json(['codigo' => 1, 'mensaje' => 'Asignaciones creadas correctamente','generacion'=> $actualizar_generacion], 200);



    }

    public function verAsignaciones(Request $request){
        $id_carrera = $request->id_carrera;
        $id_generacion = $request->id_generacion;
        try {


            // SELECCIONAMOS LOS TUTORES QUE TIENEN ASIGNADOS TUTORADOS, POR CARRERA Y GENERACION:
            $tutores = Docentes::select('docentes.*', 'tutores.id as tutor_id',
                'tutores.id_docente as tutor_id_docente')
                ->join('tutores', 'tutores.id_docente', '=', 'docentes.id')
                ->where('id_carrera', $id_carrera)
                ->distinct()
                ->get();


            //ES NECESARIO BUSCAR LOS INFORMES QUE ESTEN EN ESTATUS 1, ES DECIR, QUE ESTEN CAPTURADOS.
            $tutorados_por_tutor = ListadoTutorados::select('*', 'alumnos.id as id_alumno')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->where('alumnos.id_generacion', $id_generacion)
                ->where('alumnos.id_carrera', $id_carrera)
                ->where('alumnos.mostrar', 1)
                ->distinct('alumnos.id')

                // ->where('id_tutor', $id_tutor)
                ->get();
            // return $tutorados_por_tutor;
                // Crear un array para almacenar los resultados combinados
            $datos_combinados = array();

            // Iterar a través de los tutores
            foreach ($tutores as $tutor) {
                $tutor_id = $tutor->tutor_id;

                // Filtrar los tutorados para el tutor actual
                $tutorados = $tutorados_por_tutor->where('id_tutor', $tutor_id)->values()->all();

                // return $tutorados;

                // Combinar el docente y sus tutorados en un solo array
                $datos_combinados[] = [
                    'informes' => [
                        'total_tutorados' => 0,
                        'total_primer_informe_terminados' => 0,
                        'total_segundo_informe_terminados' => 0,
                        'total_tercer_informe_terminados' => 0,
                        'total_informes_terminados' => 0,
                        'pendientes' => 0,
                    ],
                    'docente' => $tutor->toArray(),
                    'tutorados' => $tutorados

                ];
            }

            // Convertir el resultado a JSON

            foreach ($datos_combinados as $key => $docente) {
                // Obtener la lista de tutorados para el docente actual
                $tutorados = $docente['tutorados'];
                $total_primer_informe_terminado = 0;
                $total_segundo_informe_terminado = 0;
                $total_tercer_informe_terminado = 0;
                // Inicializar contadores de informes terminados

                // Iterar a través de los tutorados
                foreach ($tutorados as $tutorado) {
                    $id_alumno = $tutorado['id_alumno'];

                    // Obtener informes para el tutorado actual
                    $primer_informe = PrimerInforme::select('*')
                        ->join('alumnos', 'alumnos.id', '=', 'primer_informe.id_alumno')
                        ->where('alumnos.id_generacion', $id_generacion)
                        ->where('alumnos.id_carrera', $id_carrera)
                        ->where('estatus_informe', 1)
                        ->where('primer_informe.id_alumno', $id_alumno)
                        ->get();

                    $segundo_informe = SegundoInforme::select('*')
                        ->join('alumnos', 'alumnos.id', '=', 'segundo_informe.id_alumno')
                        ->where('alumnos.id_generacion', $id_generacion)
                        ->where('alumnos.id_carrera', $id_carrera)
                        ->where('estatus_informe', 1)
                        ->where('segundo_informe.id_alumno', $id_alumno)
                        ->get();

                    $tercer_informe = TercerInforme::select('*')
                        ->join('alumnos', 'alumnos.id', '=', 'tercer_informe.id_alumno')
                        ->where('alumnos.id_generacion', $id_generacion)
                        ->where('alumnos.id_carrera', $id_carrera)
                        ->where('estatus_informe', 1)
                        ->where('tercer_informe.id_alumno', $id_alumno)
                        ->get();

                    // Actualizar contadores de informes terminados
                    $total_primer_informe_terminado += count($primer_informe);
                    $total_segundo_informe_terminado += count($segundo_informe);
                    $total_tercer_informe_terminado += count($tercer_informe);
                }


                // Calcular el total de informes terminados
                $total_informes_terminados = $total_primer_informe_terminado + $total_segundo_informe_terminado + $total_tercer_informe_terminado;

                // Calcular la cantidad de tutorados pendientes
                $total_tutorados = count($tutorados);
                $pendientes = $total_tutorados >= $total_informes_terminados ? $total_tutorados - $total_informes_terminados : 0;

                $datos_combinados[$key]['informes'] = [
                    'total_tutorados' => $total_tutorados , // Aquí puedes cambiarlo a $total_tutorados si es necesario
                    'total_primer_informe_terminados' => $total_primer_informe_terminado,
                    'total_segundo_informe_terminados' => $total_segundo_informe_terminado,
                    'total_tercer_informe_terminados' => $total_tercer_informe_terminado,
                    'total_informes_terminados' => $total_informes_terminados,
                    'pendientes' => $pendientes,
                ];

            }

            $resultado_json =$datos_combinados;


            return response()->json([
                'codigo' => 1,
                'data' => $resultado_json,
                'mensaje' => 'ASIGNACION DE ALUMNOS',
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function geneRepMensCoordTut(Request $request){


        $informe = $request->id_informe;  //primer_informe, segundo_informe, tercer_informe
        $periodo = $request->periodo; //1,2,3,4
        $id_periodo = $request->id_periodo; //PRIMERO, SEGUNDO, TERCERO, CUARTO

        $id_generacion = $request->input('id_generacion'); //SI 1
        $periodo = $request->input('periodo'); //SI 1
        $id_carrera = $request->input('id_carrera'); //SI 1

        $total_tutorados = 0;

        try {


            // SELECCIONAMOS LOS TUTORES QUE TIENEN ASIGNADOS TUTORADOS, POR CARRERA Y GENERACION:
            $tutores = Docentes::select('docentes.*', 'tutores.id as tutor_id',
                'tutores.id_docente as tutor_id_docente')
                ->where('id_carrera', $id_carrera)
                ->join('tutores', 'tutores.id_docente', '=', 'docentes.id')
                ->distinct()
                ->get();


            //ES NECESARIO BUSCAR LOS INFORMES QUE ESTEN EN ESTATUS 1, ES DECIR, QUE ESTEN CAPTURADOS.
            $tutorados_por_tutor = ListadoTutorados::select('*', 'alumnos.id as id_alumno')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->where('alumnos.id_generacion', $id_generacion)
                ->where('alumnos.id_carrera', $id_carrera)
                ->where('alumnos.mostrar', 1)
                // ->where('id_tutor', $id_tutor)
                ->get();

                // Crear un array para almacenar los resultados combinados
            $datos_combinados = array();

            // Iterar a través de los tutores
            foreach ($tutores as $tutor) {
                $tutor_id = $tutor->tutor_id;

                // Filtrar los tutorados para el tutor actual
                $tutorados = $tutorados_por_tutor->where('id_tutor', $tutor_id)->all();

                // Combinar el docente y sus tutorados en un solo array
                $datos_combinados[] = [
                    'informes' => [
                        'total_tutorados' => 0,
                        'total_primer_informe_terminados' => 0,
                        'total_segundo_informe_terminados' => 0,
                        'total_tercer_informe_terminados' => 0,
                        'total_informes_terminados' => 0,
                        'pendientes' => 0,
                    ],
                    'docente' => $tutor->toArray(),
                    'tutorados' => $tutorados

                ];
            }

            // Convertir el resultado a JSON

            foreach ($datos_combinados as $key => $docente) {
                // Obtener la lista de tutorados para el docente actual
                $tutorados = $docente['tutorados'];
                $total_primer_informe_terminado = 0;
                $total_segundo_informe_terminado = 0;
                $total_tercer_informe_terminado = 0;
                // Inicializar contadores de informes terminados

                // Iterar a través de los tutorados
                foreach ($tutorados as $tutorado) {
                    $id_alumno = $tutorado['id_alumno'];

                    // Obtener informes para el tutorado actual
                    $primer_informe = PrimerInforme::select('*')
                        ->join('alumnos', 'alumnos.id', '=', 'primer_informe.id_alumno')
                        ->where('alumnos.id_generacion', $id_generacion)
                        ->where('alumnos.id_carrera', $id_carrera)
                        ->where('estatus_informe', 1)
                        ->where('periodo', $periodo)
                        ->where('primer_informe.id_alumno', $id_alumno)
                        ->get();

                    $segundo_informe = SegundoInforme::select('*')
                        ->join('alumnos', 'alumnos.id', '=', 'segundo_informe.id_alumno')
                        ->where('alumnos.id_generacion', $id_generacion)
                        ->where('alumnos.id_carrera', $id_carrera)
                        ->where('estatus_informe', 1)
                        ->where('periodo', $periodo)
                        ->where('segundo_informe.id_alumno', $id_alumno)
                        ->get();

                    $tercer_informe = TercerInforme::select('*')
                        ->join('alumnos', 'alumnos.id', '=', 'tercer_informe.id_alumno')
                        ->where('alumnos.id_generacion', $id_generacion)
                        ->where('alumnos.id_carrera', $id_carrera)
                        ->where('estatus_informe', 1)
                        ->where('periodo', $periodo)
                        ->where('tercer_informe.id_alumno', $id_alumno)
                        ->get();

                    // Actualizar contadores de informes terminados
                    $total_primer_informe_terminado += count($primer_informe);
                    $total_segundo_informe_terminado += count($segundo_informe);
                    $total_tercer_informe_terminado += count($tercer_informe);
                }


                // Calcular el total de informes terminados
                $total_informes_terminados = $total_primer_informe_terminado + $total_segundo_informe_terminado + $total_tercer_informe_terminado;

                // Calcular la cantidad de tutorados pendientes
                $total_tutorados = count($tutorados);
                $pendientes = $total_tutorados >= $total_informes_terminados ? $total_tutorados - $total_informes_terminados : 0;

                $datos_combinados[$key]['informes'] = [
                    'total_tutorados' => $total_tutorados , // Aquí puedes cambiarlo a $total_tutorados si es necesario
                    'total_primer_informe_terminados' => $total_primer_informe_terminado,
                    'total_segundo_informe_terminados' => $total_segundo_informe_terminado,
                    'total_tercer_informe_terminados' => $total_tercer_informe_terminado,
                    'total_informes_terminados' => $total_informes_terminados,
                    'pendientes' => $pendientes,
                ];
                // $docente['informes'] = [
                //     'total_tutorados' => 10,
                //     'total_primer_informe_terminados' => $total_primer_informe_terminado,
                //     'total_segundo_informe_terminados' => $total_segundo_informe_terminado,
                //     'total_tercer_informe_terminados' => $total_tercer_informe_terminado,
                //     'total_informes_terminados' => $total_informes_terminados,
                //     'pendientes' => $pendientes,
                // ];
            }

            $resultado_json =$datos_combinados;


            return response()->json([
                'codigo' => 1,
                'data' => $resultado_json,
                'mensaje' => 'INFORMACION DE INFORMES',
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }



    }

}
