<?php

namespace App\Http\Controllers;

use App\Models\Alumnos;
use App\Models\AvisosPrincipales;
use App\Models\Carreras;
use App\Models\CoordinadorInstitucional;
use App\Models\CoordinadorTutorias;
use App\Models\Docentes;
use App\Models\FechasPeriodos;
use App\Models\FechasPeriodosAsignadas;
use App\Models\ListadoTutorados;
use App\Models\Periodos;
use App\Models\PrimerInforme;
use App\Models\reporte_semestral_individual;
use App\Models\SegundoInforme;
use App\Models\TercerInforme;
use App\Models\Tutores;
use ArrayObject;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use App\Models\Generaciones;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use stdClass;

class CoordinadorInstitucionalController extends Controller
{
   public function login(Request $request)
    {
        // Validar los datos


        // Realizamos una consulta para verificar los campos del usuario
        $user = DB::table('users')
        ->select('users.*', 'roles.id as id_role','roles.nombre as role_nombre')
        ->join('roles', 'users.id_rol', '=', 'roles.id')
        ->where('users.numero_checador', $request->numero_checador)
        ->where('users.password', $request->password)
        ->distinct(['users.numero_checador','users.password'])
        ->get();




        // Si el usuario existe y los campos son correctos se iniciar sesión
            if ($user) {
                $data_usuario = [];

                if($user[0]->id_rol ==4){
                    //COORDINADOR INSTITUCIONAL
                      $data_usuario = CoordinadorInstitucional::select('coordinador_institucional.*', 'carreras.id','carreras.nombre as nombre_carrera')
                     ->join('carreras', 'carreras.id', '=', 'coordinador_institucional.id_carrera')
                     ->where('coordinador_institucional.numero_checador', $user[0]->numero_checador)
                     ->where('coordinador_institucional.id_usuario', $user[0]->id)
                     ->get();
                     return response()->json(['codigo' => 1, 'mensaje' => 'Login correcto', 'usuario'=> $user, 'data_usuario'=> $data_usuario], 200);
 
                 }
                if($user[0]->id_rol ==1){
                    //ALUMNO
                    $data_usuario = Alumnos::select('alumnos.*', 'carreras.id','carreras.nombre as nombre_carrera')
                    ->join('carreras', 'carreras.id', '=', 'alumnos.id_carrera')
                    ->join('users', 'users.id', '=', 'alumnos.id_usuario')
                    ->where('alumnos.numero_checador', $user[0]->numero_checador)
                    // ->where('alumnos.id_usuario', $user->id)
                    ->first();
                    return response()->json(['codigo' => 1, 'mensaje' => 'Login correcto', 'usuario'=> $user, 'data_usuario'=> $data_usuario], 200);

                }
               
                if($user[0]->id_rol ==3){
                    //COORDINADOR TUTORIAS
                    $data_usuario = CoordinadorTutorias::select('coordinador_tutorias.*', 'carreras.id','carreras.nombre as nombre_carrera')
                    ->join('carreras', 'carreras.id', '=', 'coordinador_tutorias.id_carrera')
                    ->where('coordinador_tutorias.id_usuario', $user[0]->id)
                    ->get();
                    return response()->json(['codigo' => 1, 'mensaje' => 'Login correcto', 'usuario'=> $user, 'data_usuario'=> $data_usuario], 200);


                }
               
                if($user[0]->id_rol ==5){
                    $data_usuario = CoordinadorInstitucional::select('coordinador_institucional.*', 'carreras.id','carreras.nombre as nombre_carrera')
                    ->join('carreras', 'carreras.id', '=', 'coordinador_institucional.id_carrera')
                    ->where('coordinador_institucional.numero_checador', $user[0]->numero_checador)
                    ->where('coordinador_institucional.id_usuario', $user[0]->id)
                    ->get();
                    //DESARROLLO ACADEMICO
                    return response()->json(['codigo' => 1, 'mensaje' => 'Login correcto', 'usuario'=> $user, 'data_usuario'=> $data_usuario], 200);

                }
                if($user[0]->id_rol ==2){
                    //DOCENTE
                    $data_usuario = Docentes::select('docentes.*','carreras.id as id_carrera','carreras.nombre as nombre_carrera','users.id as id_usuario')
                    ->join('carreras', 'carreras.id', '=', 'docentes.id_carrera')
                    ->join('users', 'users.id', '=', 'docentes.id_usuario')
                    // ->where('docentes.numero_checador', $user[0]->numero_checador)
                    ->where('docentes.id_usuario', $user[0]->id)
                    ->get();
                    return response()->json(['codigo' => 1, 'mensaje' => 'Login correcto', 'usuario'=> $user, 'data_usuario'=> $data_usuario], 200);

                }




            // Redirigir a la página de inicio después de iniciar sesión
        }else{
            return response()->json(['codigo' => 0, 'mensaje' => 'Login incorrecto'], 200);
        }
    }

    public function crearAviso(Request $request){

        //Capturamos los datos del formulario:
        $titulo = $request->titulo;
        $contenido = $request->contenido;
        $fecha = Carbon::now();
        //Le metemos el formato de la fecha:
        $fecha = $fecha->format('dd-mm-yyyy hh:mm:ss A');
        $imagen_1 = $request->imagen_1;
        $imagen_2 = $request->imagen_2;
        $imagen_3 = $request->imagen_3;
        $url_1 = $request->url_1;
        $url_2 = $request->url_2;
        $url_3 = $request->url_3;
        $mostrar = true;

        //Ahora validamos que no exista un aviso con el mismo titulo:
        $buscar_aviso = AvisosPrincipales::select('*')
        ->where('titulo', $titulo)
        ->where('mostrar',1)
        ->get();

        if($buscar_aviso->count() > 0){
            return response()->json(['codigo' => 2, 'mensaje' => 'Ya existe un aviso con el mismo titulo y habilitado'], 200);
        }

        //Ahora creamos el aviso:
        $nuevo_aviso = AvisosPrincipales::create([
            'titulo' => $titulo,
            'contenido' => $contenido,
            'fecha' => $fecha,
            'imagen_1' => $imagen_1,
            'imagen_2' => $imagen_2,
            'imagen_3' => $imagen_3,
            'url_1' => $url_1,
            'url_2' => $url_2,
            'url_3' => $url_3,
            'mostrar' => $mostrar,
        ]);

        if($nuevo_aviso){
            return response()->json(['codigo' => 1, 'mensaje' => 'Aviso creado correctamente'], 200);
        }else{
            return response()->json(['codigo' => 0, 'mensaje' => 'Ocurrio un error al crear el aviso'], 200);
        }



    }
    public function verAviso(Request $request){

        //Obtenemos los avisos ordenados segun la fecha:
        $obtener_avisos = AvisosPrincipales::select('*')
        ->orderBy('fecha', 'desc')
        ->get();

        if($obtener_avisos->count() > 0){
            return response()->json(['codigo' => 1, 'mensaje' => 'Avisos obtenidos correctamente', 'data' => $obtener_avisos], 200);
        }else{
            return response()->json(['codigo' => 0, 'mensaje' => 'No existen avisos actualmente'], 200);
        }


    }
    public function validarUsuario(Request $request){

        $id_usuario = $request->id_usuario;
        $id_rol = $request->id_rol;
        $numero_cheacdor = $request->numero_checador;

        $validar_usuario = User::select('*')
        ->where('id', $id_usuario)
        ->where('id_rol', $id_rol)
        ->where('numero_checador', $numero_cheacdor)
        ->first();

        if($validar_usuario){
            return response()->json(['codigo' => 1, 'mensaje' => 'Usuario valido'], 200);
        }else{
            return response()->json(['codigo' => 0, 'mensaje' => 'Usuario no valido'], 200);
        }
    }



    // Crear generaciones:
    public function crearGeneraciones(Request $request)
    {
        //
        try {
            $encontrar_generacion = Generaciones::where('nombre', $request->input('nombre'))->first();

            if ($encontrar_generacion && $encontrar_generacion->exists()) {
                return response()->json(['codigo' => 3, 'mensaje' => 'La generacion ya existe.'], 200);
            } else {
                $nuevaGeneracion = Generaciones::create([
                    'nombre' => $request->input('nombre'),
                ]);

                if ($nuevaGeneracion) {

                    $fecha_inicio_primer_periodo =  $request->input('fecha_inicio_primer_periodo');
                    $fecha_final_primer_periodo =  $request->input('fecha_final_primer_periodo');
                    $id_fecha_periodos = '';

                    //Al crear la generacion, creamos ahora los periodos y tambien las fechas:
                    //Obtenemos el primer periodo:
                    $primerPeriodo = Periodos::select('id')->where('nombre_periodo', 'PRIMERO')->first();
                    if ($primerPeriodo && $primerPeriodo->exists()) {

                        // Ahora asignamos las fechas ala tabla correspondiente:
                        $fecha_periodos = FechasPeriodos::create([
                            'fecha_inicio' => $fecha_inicio_primer_periodo,
                            'fecha_final' => $fecha_final_primer_periodo,
                        ]);

                        if ($fecha_periodos && $fecha_periodos->exists()) {
                            //Asignamos el id ala variable, para asignarla en la otra tabla de "Fechas_periodos_asignadas"
                            $id_fecha_periodos = $fecha_periodos->id;

                            // Creamos las fechas por periodo asignadas (creamos para periodo 1,2,3,4):
                            $fecha_periodo_asignada = FechasPeriodosAsignadas::create([
                                'id_periodo' => $primerPeriodo->id,
                                'id_fecha_primera_sesion' => $id_fecha_periodos,
                                'id_fecha_segunda_sesion' => 1,
                                'id_fecha_tercera_sesion' => 1,
                                'id_generacion' => $nuevaGeneracion->id
                            ]);


                            if ($fecha_periodo_asignada && $fecha_periodo_asignada->exists()) {
                                //Creamos las fechas asignadas para los otros 3 periodos:
                                $fecha_periodo_asignada_02 = FechasPeriodosAsignadas::create([
                                    'id_periodo' => 2,
                                    'id_generacion' => $nuevaGeneracion->id,
                                    'id_fecha_primera_sesion' => 1,
                                    'id_fecha_segunda_sesion' => 1,
                                    'id_fecha_tercera_sesion' => 1,
                                ]);
                                $fecha_periodo_asignada_03 = FechasPeriodosAsignadas::create([
                                    'id_periodo' => 3,
                                    'id_generacion' => $nuevaGeneracion->id,
                                    'id_fecha_primera_sesion' => 1,
                                    'id_fecha_segunda_sesion' => 1,
                                    'id_fecha_tercera_sesion' => 1,
                                ]);
                                $fecha_periodo_asignada_04 = FechasPeriodosAsignadas::create([
                                    'id_periodo' => 4,
                                    'id_generacion' => $nuevaGeneracion->id,
                                    'id_fecha_primera_sesion' => 1,
                                    'id_fecha_segunda_sesion' => 1,
                                    'id_fecha_tercera_sesion' => 1,
                                ]);
                                return response()->json(['codigo' => 1, 'mensaje' => 'La generacion se creo correctamente.'], 200);
                            } else {
                                return response()->json(['codigo' => 3, 'mensaje' => 'Fecha periodos asignadas, no se crearon'], 500);
                            }
                        } else {
                            return response()->json(['codigo' => 3, 'mensaje' => 'Fecha inicio y final, no se crearon'], 500);
                        }
                    } else {
                        return response()->json(['codigo' => 3, 'mensaje' => 'El primer periodo, no existe.'], 500);
                    }
                } else {
                    return response()->json(['codigo' => 2, 'mensaje' => 'Ocurrio un error al insertar la generacion.'], 200);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['codigo' => 2, 'mensaje' => 'Ocurrio un error al insertar la generacion: ' . $th], 500);
        }
    }



    public function obtenerGeneraciones()
    {

        try {
            //Obtener todas las generaciones NO ASIGNADAS
            $encontrar_generaciones = Generaciones::select('*')->get();
            if ($encontrar_generaciones->count() > 0) {
                return response()->json(['codigo' => 1, 'mensaje' => 'Generaciones obtenidas.', 'data' => $encontrar_generaciones], 200);
            } else {
                return response()->json(['codigo' => 1, 'mensaje' => 'No existen generaciones actualmente.', 'data' => $encontrar_generaciones], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 2, 'mensaje' => 'Ocurrio un error al obtener las generaciones: ' . $th], 500);
        }
    }




    public function obtenerPeriodosFechasGeneracion(Request $request)
    {

        $generacionId = $request->id_generacion;

        $periodosFechas = Generaciones::select('generaciones.*', 'fechas_periodos_asignadas.*')
            ->join('fechas_periodos_asignadas', 'generaciones.id', '=', 'fechas_periodos_asignadas.id_generacion')
            ->where('generaciones.id', $generacionId)
            ->get();


        $periodosFechas = $periodosFechas->map(function ($item) {
            $primeraSesion = FechasPeriodos::find($item->id_fecha_primera_sesion);
            $segundaSesion = FechasPeriodos::find($item->id_fecha_segunda_sesion);
            $terceraSesion = FechasPeriodos::find($item->id_fecha_tercera_sesion);

            return [
                'id' => $item->id,
                'id_periodo' => $item->id_periodo,
                'id_fecha_primera_sesion' => $primeraSesion ? [
                    [
                        'fecha_inicio' => $primeraSesion->fecha_inicio,
                        'fecha_final' => $primeraSesion->fecha_final,
                    ],
                ] : [],
                'id_fecha_segunda_sesion' => $segundaSesion ? [
                    [
                        'fecha_inicio' => $segundaSesion->fecha_inicio,
                        'fecha_final' => $segundaSesion->fecha_final,
                    ],
                ] : [],
                'id_fecha_tercera_sesion' => $terceraSesion ? [
                    [
                        'fecha_inicio' => $terceraSesion->fecha_inicio,
                        'fecha_final' => $terceraSesion->fecha_final,
                    ],
                ] : [],
                'id_generacion' => $item->id_generacion,
            ];
        });

        return response()->json($periodosFechas);
    }



    public function actualizarFechasGeneracion(Request $request)
    {
        //Buscamos el registro:
        $fechas_periodos = FechasPeriodosAsignadas::select('*')
            ->where('id_periodo', $request->id_periodo)
            ->where('id_generacion', $request->id_generacion)
            ->get();
        //Creamos en la tabla nuevas fechas y despues las actualizamos al registro anterior:
        $fecha_periodos_01 = FechasPeriodos::create([
            'fecha_inicio' => $request->fechaInicialPrimeraSesion,
            'fecha_final' => $request->fechaFinalPrimeraSesion,
        ]);
        $fecha_periodos_02 = FechasPeriodos::create([
            'fecha_inicio' => $request->fechaInicialSegundaSesion,
            'fecha_final' => $request->fechaFinalSegundaSesion,
        ]);
        $fecha_periodos_03 = FechasPeriodos::create([
            'fecha_inicio' => $request->fechaInicialTerceraSesion,
            'fecha_final' => $request->fechaFinalTerceraSesion,
        ]);

        //Actualizar fechas segun la generacion:

        // $actualizarFechas = FechasPeriodosAsignadas::where('id_periodo', $fechas_periodos[0]->id_periodo)
        //     ->where('id_generacion', $fechas_periodos[0]->id_generacion)
        //     ->update([
        //         // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
        //         'id_fecha_primera_sesion' =>  $fecha_periodos_01->id,
        //         'id_fecha_segunda_sesion' =>  $fecha_periodos_02->id,
        //         'id_fecha_tercera_sesion' =>   $fecha_periodos_03->id
        //         // ... y así sucesivamente
        //     ]);

        //Actualizar fechas a todas las generaciones:
        $actualizarFechas = FechasPeriodosAsignadas::where('id_periodo', $fechas_periodos[0]->id_periodo)
            ->update([
                // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
                'id_fecha_primera_sesion' =>  $fecha_periodos_01->id,
                'id_fecha_segunda_sesion' =>  $fecha_periodos_02->id,
                'id_fecha_tercera_sesion' =>   $fecha_periodos_03->id
                // ... y así sucesivamente
            ]);


        if ($actualizarFechas > 0) {
            return response()->json(['codigo' => 1, 'mensaje' => 'Fechas Actualizadas.', 'data' => $actualizarFechas], 200);
        } else {
            return response()->json(['codigo' => 2, 'mensaje' => 'No se actualizaron fechas.', 'data' => $actualizarFechas], 200);
        }
    }



    public function cargarNuevosAlumnos(Request $request)
    {
        $id_generacion = $request->id_generacion;
        $array_alumnos = $request->lista_alumnos;

        usleep(7000000);
        $id_generacion = $request->id_generacion;
        $array_alumnos = $request->lista_alumnos;

        // Número máximo de reintentos
        $maxRetries = 3;
        $id_nuevo_alumno=0;
        foreach ($array_alumnos as $alumno) {
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                $carrera=$alumno['ESPECIALIDAD'];
                try {
                    $buscar_alumno = Alumnos::select('alumnos.*', 'carreras.*', 'users.*')
                        ->join('carreras', 'carreras.id', '=', 'alumnos.id_carrera')
                        ->join('users', 'users.id', '=', 'alumnos.id_usuario')
                        ->where('alumnos.numero_checador', $alumno['NUMERO_CONTROL'])
                        ->where('users.id_rol',1)
                        ->get();

                    if ($buscar_alumno->count() > 0 && $buscar_alumno->isNotEmpty())  {
                        //Si lo encuentra actualizamos el semestre:

                        $actualizarAlumno = Alumnos::where('numero_checador', $alumno['NUMERO_CONTROL'])
                            ->update([
                                // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
                                'numero_checador' => $alumno['NUMERO_CONTROL'],
                            ]);

                        continue;

                        // return response()->json(['codigo'=> 1,'mensaje' => 'Alumno actualizado.', 'data' => $actualizarAlumno], 200);

                    } else {
                        //VERIFICAMOS QUE NO EXISTA UN USUARIO CON EL NAME, PASSWORD Y NUMERO CHECADOR:
                        $buscar_usuario = User::select('*')
                            ->where('numero_checador', $alumno['NUMERO_CONTROL'])
                            ->where('id_rol',1)

                            ->get();


                        $crear_nuevo_usuario = '';

                        if ($buscar_usuario->count() > 0) {
                            //Si lo encuentra actualizamos el semestre:
                            $crear_nuevo_usuario = User::where('numero_checador', $alumno['NUMERO_CONTROL'])
                            ->where('id_rol',1)
                            ->update([
                                    // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
                                    'numero_checador' => $alumno['NUMERO_CONTROL'],
                                ]);

                            //Obtenemos los datos del usuario que actualizamos:
                            $crear_nuevo_usuario = User::select('*')
                                ->where('numero_checador', $alumno['NUMERO_CONTROL'])
                                ->where('id_rol',1)
                                ->get();

                             $id_nuevo_alumno= $crear_nuevo_usuario[0]->id;   
                        } else {
                            //Ahora cremos usuario con rol DOCENTE:
                            $crear_nuevo_usuario = User::create([
                                'name' => $alumno['NUMERO_CONTROL'],
                                'password' => $alumno['NUMERO_CONTROL'],
                                'numero_checador' => $alumno['NUMERO_CONTROL'],
                                'id_rol' => 1,
                            ]);

                            $id_nuevo_alumno= $crear_nuevo_usuario->id;   

                        }

                        //Si creo el usuario lo agregamos ala tabla de alumnos:
                        if ($crear_nuevo_usuario) {

                          

                            try {

                                $id_carrera =  Carreras::where('nombre',  $carrera)->where('mostrar', 1)->first();


                                if ($id_carrera) {
                                    $nuevo_alumno = Alumnos::create([
                                        'nombre' => $alumno['NOMBRES'],
                                        'apellido_paterno' => $alumno['APELLIDO_PATERNO'],
                                        'apellido_materno' => $alumno['APELLIDO_MATERNO'],
                                        'numero_checador' => $alumno['NUMERO_CONTROL'],
                                        'estatus_actual' => $alumno['ESTATUS_ACTUAL'],
                                        'semestre' => $alumno['SEMESTRE_ACTUAL'],
                                        'especialidad' => $alumno['ESPECIALIDAD'],
                                         'sexo' => $alumno['SEXO'],
                                        'id_carrera' => $id_carrera->id,
                                        'mostrar' => 1,
                                        'id_usuario' => $id_nuevo_alumno,
                                        'id_generacion' => $request->id_generacion
                                    ]);

                                        continue;

                                } else {
                                    continue;
                                }
                            } catch (\Throwable $th) {
                                return "OCURRIO EL SIGUIENTE ERROR: ".$th."  AL BUSCAR LA CARRERA. " ;

                            }


                        } else {
                            continue;
                        }
                    }
                } catch (RequestException $e) {
                    // Si ocurrió un error, espera un tiempo antes de reintentar
                    if ($attempt < $maxRetries) {
                        usleep(7000000); // Espera 7 segundo antes de reintentar
                    } else {
                        // Si se agotaron los reintentos, maneja el error
                        // Puedes lanzar una excepción o guardar información sobre el error
                        // ...
                        // Luego, sigue con el siguiente alumno
                        break;
                    }
                }
            }
        }

        //Actualizamos la generacion a alumnos asignados:
        $actualizar_generacion = Generaciones::where('id', $id_generacion)
            ->update([
                // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
                'estatus_asignada' => "SI"
            ]);

        // Al finalizar el procesamiento de los alumnos, responde al cliente
        return response()->json(['codigo' => 1, 'mensaje' => 'Alumnos actualizados y/o agregados', 'GENERACION ACTUALIZADA' => $actualizar_generacion], 200);
    }







    public function cargarDocentes(Request $request)
    {

        usleep(7000000);
        $array_docentes = $request->lista_docentes;

        // Número máximo de reintentos
        $maxRetries = 3;

        $correo_institucional = '';

        foreach ($array_docentes as $docente) {
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {

                    $correo_institucional = $docente['CORREO_INSTITUCIONAL'];
                    // print_r($docente);
                    //Verificamos que el docente exista:
                    $buscar_docente = Docentes::select('docentes.*', 'users.*')
                        ->join('users', 'users.id', '=', 'docentes.id_usuario')
                        ->where('docentes.numero_checador', $docente['NUMERO_CHECADOR'])
                        ->where('users.id_rol',2)
                        ->get();



                    if ($buscar_docente->count() > 0 && $buscar_docente->isNotEmpty()) {
                        //Si lo encuentra actualizamos el semestre:

                        $actualizarDocente = Docentes::where('numero_checador', $docente['NUMERO_CHECADOR'])
                            ->update([
                                // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
                                'numero_checador' => $docente['NUMERO_CHECADOR'],
                            ]);

                        continue;

                        // return response()->json(['codigo'=> 1,'mensaje' => 'Alumno actualizado.', 'data' => $actualizarAlumno], 200);

                    } else {

                        //VERIFICAMOS QUE NO EXISTA UN USUARIO CON EL NAME, PASSWORD Y NUMERO CHECADOR:
                        $buscar_usuario = User::select('*')
                            ->where('numero_checador', $docente['NUMERO_CHECADOR'])
                            ->where('id_rol',2)
                            ->get();


                        $crear_nuevo_usuario = '';
                        if ($buscar_usuario->count() > 0) {
                            //Si lo encuentra actualizamos.
                            $crear_nuevo_usuario = User::where('numero_checador', $docente['NUMERO_CHECADOR'])
                            ->where('id_rol',2)
                                ->update([
                                    // Aquí especifica los campos que deseas actualizar junto con sus nuevos valores
                                    'numero_checador' => $docente['NUMERO_CHECADOR'],
                                ]);

                            //Obtenemos los datos del usuario que actualizamos:
                            $crear_nuevo_usuario = User::select('*')
                                ->where('numero_checador', $docente['NUMERO_CHECADOR'])
                                ->where('id_rol',2)
                                ->get();
                        } else {
                            //Ahora cremos usuario con rol DOCENTE:
                            $crear_nuevo_usuario = User::create([
                                'name' => $docente['NUMERO_CHECADOR'],
                                'password' => $docente['NUMERO_CHECADOR'],
                                'numero_checador' => $docente['NUMERO_CHECADOR'],
                                'id_rol' => 2,
                            ]);
                        }


                        //Si creo el usuario lo agregamos ala tabla de DOCENTES:
                        if ($crear_nuevo_usuario) {


                            try {
                                $id_carrera =  Carreras::where('nombre', $docente['CARRERA'])->where('mostrar', 1)->first();

                            //Lanzamos un echo con Swal de javascript para que se muestre en el front:


                               //Si la carrera NO es null (SI SE ENCUENTRA REGISTRADA):
                            if ($id_carrera) {

                                $nuevo_docente = Docentes::create([
                                    'nombre' => $docente['NOMBRES'],
                                    'apellido_paterno' => $docente['APELLIDO_PATERNO'],
                                    'apellido_materno' => $docente['APELLIDO_MATERNO'],
                                    'numero_checador' => $docente['NUMERO_CHECADOR'],
                                    'correo_institucional' => $correo_institucional,
                                    'id_carrera' => $id_carrera->id,
                                    'mostrar' => 1,
                                    'id_usuario' => $crear_nuevo_usuario->id,
                                ]);



                                if ($nuevo_docente) {
                                    //Si se creo el docente, entonces ahora lo asignamos ala tabla de tutores:
                                    $nuevo_tutor = Tutores::create([
                                        'id_docente' => $nuevo_docente->id,
                                        'id_coordinador_institucional' => null,
                                        'mostrar' => 1,
                                        'id_usuario' => $crear_nuevo_usuario->id,
                                    ]);

                                    if ($nuevo_tutor) {
                                        continue;
                                    } else {
                                        continue;
                                        // return "NO SE ENCONTRO EL TUTOR";
                                    }
                                } else {
                                    continue;

                                    // return "NO SE CREO EL DOCENTE";
                                }
                            } else {
                                continue;

                                // return "NO SE ENCONTRO LA CARRERA";
                            }

                            } catch (\Throwable $th) {
                                // throw $th;
                                //convertimos el docente[] en json:
                                return "OCURRIO EL SIGUIENTE ERROR: ".$th."  AL BUSCAR LA CARRERA: " ;
                            }




                        } else {
                           continue;
                            // return "NO SE CREO EL USUARIO";
                        }
                    }
                } catch (RequestException $e) {
                    // Si ocurrió un error, espera un tiempo antes de reintentar
                    if ($attempt < $maxRetries) {
                        usleep(7000000); // Espera 1 segundo antes de reintentar
                    } else {
                        // Si se agotaron los reintentos, maneja el error
                        // Puedes lanzar una excepción o guardar información sobre el error
                        // ...
                        // Luego, sigue con el siguiente alumno
                        break;
                    }
                }
            }
        }

        // Al finalizar el procesamiento de los alumnos, responde al cliente
        return response()->json(['codigo' => 1, 'mensaje' => 'DOCENTES actualizados y/o agregados'], 200);
    }




    public function obtenerDocentesCarrera(Request $request)
    {

        try {
            $lista_docentes_carrera_tutores = Docentes::select('docentes.*', 'tutores.*')
                ->join('tutores', 'tutores.id_docente', '=', 'docentes.id')
                ->where('docentes.id_carrera', $request->id_carrera)
                ->where('tutores.mostrar', 1)
                ->get();

            $lista_docentes_no_tutores = Docentes::select('docentes.*')
                ->leftJoin('tutores', 'tutores.id_docente', '=', 'docentes.id')
                ->where('docentes.id_carrera', $request->id_carrera)
                ->whereNull('tutores.id') // Filtrar aquellos docentes que no tienen correspondencia en la tabla de tutores
                ->get();
            return response()->json(['codigo' => 1, 'mensaje' => 'Docentes obtenidos,', 'docentes_tutores' => $lista_docentes_carrera_tutores, 'docentes_no_tutores' => $lista_docentes_no_tutores], 200);
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 2, 'mensaje' => 'OCURRIO UN ERROR AL OBTENER LA LISTA DE DOCENTES. ' . $th], 200);
        }
    }

    public function obtenerCarreras()
    {

        try {
            $lista_carreras = Carreras::select('id', 'nombre', 'mostrar')
                ->where('mostrar', 1)
                ->distinct('nombre')
                ->get();
            return response()->json(['codigo' => 1, 'mensaje' => 'Se obtuvieron las carreras.', 'data' => $lista_carreras], 200);
        } catch (\Throwable $th) {
            return response()->json(['codigo' => 2, 'mensaje' => 'OCURRIO UN ERROR AL OBTENER LA LISTA DE CARRERAS. ' . $th], 200);
        }
    }


    public function asignarTutorCarrera(Request $request)
    {

        try {
            $nuevo_tutor = Tutores::create([
                'id_docente' => $request->id,
                'id_coordinador_institucional' => null,
                'mostrar' => 1,
                'id_usuario' => $request->id_usuario,
            ]);

            if ($nuevo_tutor) {
                return response()->json(['codigo' => 1, 'mensaje' => 'Tutor creado correctamente. ', 'data' => $nuevo_tutor], 200);
            } else {
                return response()->json(['codigo' => 2, 'mensaje' => 'NO se pudo crear el TUTOR. '], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['codigo' => 2, 'mensaje' => 'OCURRIO UN ERROR AL ASIGNAR TUTOR. ' . $th], 200);
        }
    }


    public function generarReportesSemestrales(Request $request)
    {
        $id_carrera = $request->input('id_carrera');
        $id_generacion = $request->input('id_generacion');
        $id_periodo = $request->input('id_periodo');
        $periodo = $request->input('periodo');

        //=========================================================================
        //=========================================================================
        //=========================================================================

        $total_tutores_asignados = ListadoTutorados::join('tutores', 'tutores.id', '=', 'listado_tutorados.id_tutor')
            ->join('docentes', 'docentes.id', '=', 'tutores.id_docente')
            ->where('docentes.id_carrera', $id_carrera)
            ->distinct('listado_tutorados.id_tutor')
            ->count('listado_tutorados.id_tutor');


        //$total_tutores_asignados = ListadoTutorados::select('listado_tutorados.*', 'alumnos.id_carrera as ALUMNOS_id_carrera', 'alumnos.id as ALUMNOS_id', 'alumnos.id_generacion as ALUMNO_id_generacion')
            //->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
            //->where('listado_tutorados.id_generacion', $id_generacion)
            //->where('alumnos.id_carrera', $id_carrera)
        //    // ->where('listado_tutorados.id_tutor', $id_tutor) // Agrega esta condición
            //->distinct(['alumnos.id_carrera', 'listado_tutorados.id_generacion']) // Aplica distinct al campo id_tutor
            //->count();



        //=========================================================================
        //=========================================================================
        //=========================================================================

        $reportes_alumnos = reporte_semestral_individual::select('reporte_semestral_individual.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'reporte_semestral_individual.id_alumno')
            ->where('reporte_semestral_individual.periodo', $periodo)
            ->where('reporte_semestral_individual.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->get();
        $numero_total_alumnos_asignados_carrera = $reportes_alumnos->count();


        $lista_alumnos = ListadoTutorados::select('listado_tutorados.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
            ->where('listado_tutorados.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->get();

        $numero_total_alumnos_matricula_carrera = $lista_alumnos->count();


        //Guardamos en un array , cada id de los alumnos:
        $array_id_alumnos = [];
        foreach ($reportes_alumnos as $alumno) {
            array_push($array_id_alumnos, $alumno->id_alumno);
        }


        //=========================================================================
        //=========================================================================
        //=========================================================================

        //SE AMPLIO LA CONSULTA DE ALUMNOS CON DIAGNOSTICO AGREGANDO EL ID DE CARRERA
        $numero_total_alumnos_con_diagnostico = PrimerInforme::select('primer_informe.*', 'registro_diagnostico')
        ->join('alumnos', 'alumnos.id', '=', 'primer_informe.id_alumno')
        ->where('primer_informe.id_generacion', $id_generacion)
        ->where('primer_informe.periodo', $periodo)
        ->where('alumnos.id_carrera', $id_carrera)
        ->where('alumnos.id_generacion', $id_generacion)
        ->get();
        
        $numero_total_alumnos_con_diagnostico = $numero_total_alumnos_con_diagnostico->filter(function ($item) {
            return $item->registro_diagnostico == 'SI'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $numero_total_alumnos_con_diagnostico = $numero_total_alumnos_con_diagnostico->count();
    
        //$numero_total_alumnos_con_diagnostico = ($numero_total_alumnos_asignados_carrera) - $numero_total_alumnos_con_diagnostico;

        // Asegurarse de que $numero_total_alumnos_asignados_carrera no sea cero
        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_con_diagnostico = ($numero_total_alumnos_con_diagnostico*100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_con_diagnostico = 0; // O manejarlo de otra manera según tus necesidades
        }

        





        /*$numero_total_alumnos_con_diagnostico = PrimerInforme::select('primer_informe.registro_diagnostico')
            ->join('alumnos', 'alumnos.id', '=', 'primer_informe.id_alumno')
            ->where('id_generacion', $id_generacion)  
            ->where('periodo', $periodo)
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('registro_diagnostico', 'SI'); // Filtra directamente en la consulta  
            //->count(); // Cuenta directamente los registros que cumplen la condición  


        // Asegurarse de que $numero_total_alumnos_asignados_carrera no sea cero  
        if ($numero_total_alumnos_asignados_carrera != 0) {  
            $porcentaje_alumnos_con_diagnostico = ($numero_total_alumnos_con_diagnostico * 100) / $numero_total_alumnos_asignados_carrera;  
        } else {  
            $porcentaje_alumnos_con_diagnostico = 0; // O manejarlo de otra manera según tus necesidades  
        }*/

        //=========================================================================
        //=========================================================================
        //=========================================================================



        $numero_total_alumnos_atendidos_1 = reporte_semestral_individual::select('reporte_semestral_individual.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'reporte_semestral_individual.id_alumno')
            ->where('reporte_semestral_individual.periodo', $periodo)
            ->where('reporte_semestral_individual.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->where('reporte_semestral_individual.numero_sesiones_totales', '=', 1)
            ->get();
        $numero_total_alumnos_atendidos_1 = $numero_total_alumnos_atendidos_1->count();


        // Asegurarse de que $numero_total_alumnos_asignados_carrera no sea cero
        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_1 = ($numero_total_alumnos_atendidos_1 * 100) / $numero_total_alumnos_matricula_carrera;
        } else {
            $porcentaje_alumnos_atendidos_1 = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================






        $numero_total_alumnos_atendidos_2 = reporte_semestral_individual::select('reporte_semestral_individual.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'reporte_semestral_individual.id_alumno')
            ->where('reporte_semestral_individual.periodo', $periodo)
            ->where('reporte_semestral_individual.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->where('reporte_semestral_individual.numero_sesiones_totales', '=', 2)
            ->get();
        $numero_total_alumnos_atendidos_2 = $numero_total_alumnos_atendidos_2->count();

        //Sacamos el porcentaje segun el numero_total_alumnos_asignados_carrera:
        // Asegurarse de que $numero_total_alumnos_asignados_carrera no sea cero
        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_2 = ($numero_total_alumnos_atendidos_2 * 100) / $numero_total_alumnos_matricula_carrera;
        } else {
            $porcentaje_alumnos_atendidos_2 = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================


        $numero_total_alumnos_atendidos_3 = reporte_semestral_individual::select('reporte_semestral_individual.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'reporte_semestral_individual.id_alumno')
            ->where('reporte_semestral_individual.periodo', $periodo)
            ->where('reporte_semestral_individual.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->where('reporte_semestral_individual.numero_sesiones_totales', '=', 3)
            ->get();
        $numero_total_alumnos_atendidos_3 = $numero_total_alumnos_atendidos_3->count();

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_3 = ($numero_total_alumnos_atendidos_3 * 100) / $numero_total_alumnos_matricula_carrera;
        } else {
            $porcentaje_alumnos_atendidos_3 = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================


        $alumnos_atendidos_forma_grupal = reporte_semestral_individual::select('modalidadPrep')->where('id_generacion', $id_generacion)->where('periodo', $periodo)
            ->get();
        $alumnos_atendidos_forma_grupal = $alumnos_atendidos_forma_grupal->filter(function ($item) {
            return $item->modalidadPrep == 'GR'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $alumnos_atendidos_forma_grupal_total = $alumnos_atendidos_forma_grupal->count();


        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_forma_grupal = ($alumnos_atendidos_forma_grupal_total * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_forma_grupal = 0; // O manejarlo de otra manera según tus necesidades
        }


        //=========================================================================
        //=========================================================================
        //=========================================================================



        $alumnos_atendidos_forma_individual = reporte_semestral_individual::select('modalidadPrep')->where('id_generacion', $id_generacion)->where('periodo', $periodo)
            ->get();
        $alumnos_atendidos_forma_individual = $alumnos_atendidos_forma_individual->filter(function ($item) {
            return $item->modalidadPrep == 'IN'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $alumnos_atendidos_forma_individual_total = $alumnos_atendidos_forma_individual->count();

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_forma_individual = ($alumnos_atendidos_forma_individual_total * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_forma_individual = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================


        $alumnos_atendidos_forma_ambas = reporte_semestral_individual::select('modalidadPrep')->where('id_generacion', $id_generacion)->where('periodo', $periodo)
            ->get();
        $alumnos_atendidos_forma_ambas = $alumnos_atendidos_forma_ambas->filter(function ($item) {
            return $item->modalidadPrep == 'AM'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $alumnos_atendidos_forma_ambas_total = $alumnos_atendidos_forma_ambas->count();

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_forma_ambas = ($alumnos_atendidos_forma_ambas_total * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_forma_ambas = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================

        //AJUSTE NO ATENDIDOS POR INASISTENCIA 17/10/2024
        $alumnos_atendidos_forma_inasistencia = $numero_total_alumnos_matricula_carrera - ($numero_total_alumnos_atendidos_1 + $numero_total_alumnos_atendidos_2 + $numero_total_alumnos_atendidos_3);

        
        /*
        //Calculo de alumno que no asistieron
        $alumnos_atendidos_forma_inasistencia = $numero_total_alumnos_asignados_carrera - ($numero_total_alumnos_atendidos_1 + $numero_total_alumnos_atendidos_2 + $numero_total_alumnos_atendidos_3);
        //$alumnos_atendidos_forma_inasistencia = $numero_total_alumnos_asignados_carrera - ($alumnos_atendidos_forma_grupal_total + $alumnos_atendidos_forma_individual_total + $alumnos_atendidos_forma_ambas_total);*/
        if ($numero_total_alumnos_asignados_carrera != 0) {
            $alumnos_atendidos_forma_inasistencia_porcentaje = ($alumnos_atendidos_forma_inasistencia * 100) / $numero_total_alumnos_matricula_carrera;
        } else {
            $alumnos_atendidos_forma_inasistencia_porcentaje = 0; // O manejarlo de otra manera según tus necesidades
        }
        

        //=========================================================================
        //=========================================================================
        //=========================================================================





        // Asegurarse de que $numero_total_alumnos_asignados_carrera no sea cero
        /*if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_con_diagnostico = ($numero_total_alumnos_con_diagnostico * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_con_diagnostico = 0; // O manejarlo de otra manera según tus necesidades
        }*/




        $numero_total_alumnos_atendidos_0 = reporte_semestral_individual::select('reporte_semestral_individual.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'reporte_semestral_individual.id_alumno')
            ->where('reporte_semestral_individual.periodo', $periodo)
            ->where('reporte_semestral_individual.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->where('reporte_semestral_individual.numero_sesiones_totales', '=', 0)
            ->get();
        $numero_total_alumnos_atendidos_0 = $numero_total_alumnos_atendidos_0->count();

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_0 = ($numero_total_alumnos_atendidos_0 * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_0 = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================



        //Buscamos por cada alumno, si en algun informe fue atendido con la clave "SP" SERVICIOS PSICOLOGICOS:

        $alumnos_atendidos_piscologia = 0;
        $alumnos_atendidos_academica = 0;
        $alumnos_atendidos_becas = 0;
        $alumnos_atendidos_salud = 0;
        $alumnos_atendidos_instancias_extras = 0;
        $alumnos_atendidos_otras = 0;


        //Por cada id del array de alumnos, buscamos en el primer,segundo y tercer informe, pero debemos para cuando lo encuentre e ir sumando:
        foreach ($array_id_alumnos as $id_alumno) {
            //Buscamos en el primer informe:
            $buscar_primer_informe = PrimerInforme::select('tipo_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
            if ($buscar_primer_informe) {
                if ($buscar_primer_informe->tipo_canalizacion == 'SP') {
                    $alumnos_atendidos_piscologia++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'IE') {
                    $alumnos_atendidos_instancias_extras++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'AC') {
                    $alumnos_atendidos_academica++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'SS') {
                    $alumnos_atendidos_salud++;
                    continue;
                }

                if ($buscar_primer_informe->tipo_canalizacion == 'BE') {
                    $alumnos_atendidos_becas++;
                    continue;
                } else {
                    //$alumnos_atendidos_otras++;
                    //continue;
                }
            }

            //Buscamos en el segundo informe:
            $buscar_segundo_informe = SegundoInforme::select('tipo_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
            if ($buscar_segundo_informe) {
                if ($buscar_primer_informe->tipo_canalizacion == 'SP') {
                    $alumnos_atendidos_piscologia++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'IE') {
                    $alumnos_atendidos_instancias_extras++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'AC') {
                    $alumnos_atendidos_academica++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'SS') {
                    $alumnos_atendidos_salud++;
                    continue;
                }

                if ($buscar_primer_informe->tipo_canalizacion == 'BE') {
                    $alumnos_atendidos_becas++;
                    continue;
                } else {
                    //$alumnos_atendidos_otras++;
                    //continue;
                }
            }

            //Buscamos en el tercer informe:
            $buscar_tercer_informe = TercerInforme::select('tipo_canalizacion')->where('id_alumno', $id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
            if ($buscar_tercer_informe) {
                if ($buscar_primer_informe->tipo_canalizacion == 'SP') {
                    $alumnos_atendidos_piscologia++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'IE') {
                    $alumnos_atendidos_instancias_extras++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'AC') {
                    $alumnos_atendidos_academica++;
                    continue;
                }
                if ($buscar_primer_informe->tipo_canalizacion == 'SS') {
                    $alumnos_atendidos_salud++;
                    continue;
                }

                if ($buscar_primer_informe->tipo_canalizacion == 'BE') {
                    $alumnos_atendidos_becas++;
                    continue;
                } else {
                    //$alumnos_atendidos_otras++;
                    //continue;
                }
            }
        }


        //Ahora sacamos los porcentajes de los alumnos atendidos:
        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_piscologia = ($alumnos_atendidos_piscologia * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_piscologia = 0; // O manejarlo de otra manera según tus necesidades
        }

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_instancias_extras = ($alumnos_atendidos_instancias_extras * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_instancias_extras = 0; // O manejarlo de otra manera según tus necesidades
        }

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_academica = ($alumnos_atendidos_academica * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_academica = 0; // O manejarlo de otra manera según tus necesidades
        }

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_salud = ($alumnos_atendidos_salud * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_salud = 0; // O manejarlo de otra manera según tus necesidades
        }

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_becas = ($alumnos_atendidos_becas * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_becas = 0; // O manejarlo de otra manera según tus necesidades
        }

        $alumnos_atendidos_otras = $numero_total_alumnos_matricula_carrera - ($alumnos_atendidos_piscologia + $alumnos_atendidos_instancias_extras + $alumnos_atendidos_academica + $alumnos_atendidos_salud + $alumnos_atendidos_becas);

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_otras = ($alumnos_atendidos_otras * 100) / $numero_total_alumnos_matricula_carrera;
        } else {
            $porcentaje_alumnos_atendidos_otras = 0; // O manejarlo de otra manera según tus necesidades
        }



        // Inicializa arreglos para almacenar los valores de cada columna
        $asistenciasArray = [];
        $sesionesArray = [];
        $horasArray = [];
        $canalizacionesArray = [];
        $actividadArray = [];
        $situacionArray = [];
        $logrosArray = [];

        foreach ($reportes_alumnos as $row) {
            // Almacena los valores de cada columna en el arreglo correspondiente
            $asistenciasArray[] = $row->numero_total_asistencias;
            $sesionesArray[] = $row->numero_sesiones_totales;
            $horasArray[] = $row->numero_horas_totales;
            $canalizacionesArray[] = $row->totalNumeroCanalizaciones;
            $actividadArray[] = $row->clave_prep_actividad;
            $situacionArray[] = $row->clave_prep_situacion;
            $logrosArray[] = $row->clave_prep_logros;
        }

        // Encuentra el valor más repetido en cada arreglo
        $valorMasRepetidoAsistencias = array_reduce($asistenciasArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoAsistencias);
        $valorMasRepetidoAsistencias = key($valorMasRepetidoAsistencias);

        $valorMasRepetidoSesiones = array_reduce($sesionesArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoSesiones);
        $valorMasRepetidoSesiones = key($valorMasRepetidoSesiones);

        $valorMasRepetidoHoras = array_reduce($horasArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoHoras);
        $valorMasRepetidoHoras = key($valorMasRepetidoHoras);

        $valorMasRepetidoCanalizaciones = array_reduce($canalizacionesArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoCanalizaciones);
        $valorMasRepetidoCanalizaciones = key($valorMasRepetidoCanalizaciones);

        $valorMasRepetidoActividad = array_reduce($actividadArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoActividad);
        $valorMasRepetidoActividad = key($valorMasRepetidoActividad);

        $valorMasRepetidoSituacion = array_reduce($situacionArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoSituacion);
        $valorMasRepetidoSituacion = key($valorMasRepetidoSituacion);

        $valorMasRepetidoLogros = array_reduce($logrosArray, function ($carry, $item) {
            $carry[$item] = ($carry[$item] ?? 0) + 1;
            return $carry;
        }, []);
        arsort($valorMasRepetidoLogros);
        $valorMasRepetidoLogros = key($valorMasRepetidoLogros);

        // Ahora tienes los valores más repetidos en cada columna
        // Calcula la suma total de asistencias, sesiones y horas
        $sumaTotalAsistencias = array_sum($asistenciasArray);
        $sumaTotalSesiones = array_sum($sesionesArray);
        $sumaTotalHoras = array_sum($horasArray);

    
        //Obtenemos la lista de tutores asignados, que usaremos para todas las demas consultas:
        $lista_tutores_asignados = ListadoTutorados::select('listado_tutorados.id_tutor', 'tutores.*', 'docentes.*')
            ->join('tutores', 'tutores.id', '=', 'listado_tutorados.id_tutor')
            ->join('docentes', 'docentes.id', '=', 'tutores.id_docente')
            ->where('listado_tutorados.id_generacion', $id_generacion)
            ->distinct('listado_tutorados.id_tutor') // Aplica distinct al campo id_tutor
            ->get();
          
        
        $conteo_tutores_asignados = $lista_tutores_asignados->count();
        //Guardamos en un array todas las clves de los tutores:
        $array_id_tutores = [];
        foreach ($lista_tutores_asignados as $tutor) {
            array_push($array_id_tutores, $tutor->id_tutor);
        }



        //$lista_tutores_asignados_numero = count($array_id_tutores);



        //=====================================================================
        // INFORME GENERAL
        //=====================================================================


        $array_tutores_informacion = array();

        //Por cada tutor en "$lista_tutores_asignados" vamos a encontrar los tutoradops asignados:
        $lista_tutorados_asignados = ListadoTutorados::select('listado_tutorados.id_tutor', 'tutores.*', 'docentes.*')
            ->join('tutores', 'tutores.id', '=', 'listado_tutorados.id_tutor')
            ->join('docentes', 'docentes.id', '=', 'tutores.id_docente')
            ->where('listado_tutorados.id_generacion', $id_generacion)
            ->where('docentes.id_carrera', $id_carrera)
            ->distinct(['listado_tutorados.id_alumno', 'listado_tutorados.id_generacion']) // Aplica distinct al campo id_tutor
            ->get();

        $lista_tutorados_asignados_numero = $lista_tutorados_asignados->count();

        //Recorremos segun los tutores:
        for ($i = 0; $i < $lista_tutorados_asignados_numero; $i++) {
            $informe_general = new stdClass();
            $informe_general->numero_checador = $lista_tutorados_asignados[$i]->numero_checador;
            $informe_general->nombre_tutor = $lista_tutorados_asignados[$i]->nombre . " " . $lista_tutorados_asignados[$i]->apellido_paterno . " " . $lista_tutorados_asignados[$i]->apellido_materno;
            $lista_alumnos_del_tutor = ListadoTutorados::select('listado_tutorados.*')->where('id_tutor', $lista_tutorados_asignados[$i]->id_tutor)->get();
            $informe_general->lista_alumnos_del_tutor = $lista_alumnos_del_tutor;
            $informe_general->tutorados_asignados = $lista_alumnos_del_tutor->count();

            $atendidos_grupal_total = 0;
            $atendidos_individual_total = 0;
            $atendidos_ambas_total = 0;
            $atendidos_NA_total = 0;

            //Por cada id del array de alumnos, buscamos en el primer,segundo y tercer informe, pero debemos para cuando lo encuentre e ir sumando:
            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                //Buscamos en el primer informe:
                $buscar_primer_informe = PrimerInforme::select('modalidad')->where('id_alumno', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
                if ($buscar_primer_informe) {
                    if ($buscar_primer_informe->modalidad == 'GR') {
                        $lista_modalidad_preponderante[] = 'GR';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'AM') {
                        $lista_modalidad_preponderante[] = 'AM';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'IN') {
                        $lista_modalidad_preponderante[] = 'IN';

                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'NA' || $buscar_primer_informe->modalidad == 'N/A') {
                        $lista_modalidad_preponderante[] = 'NA';
                    }
                }
                //Buscamos en el segundo informe:
                $buscar_segundo_informe = SegundoInforme::select('modalidad')->where('id_alumno', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
                if ($buscar_segundo_informe) {
                    if ($buscar_primer_informe->modalidad == 'GR') {
                        $lista_modalidad_preponderante[] = 'GR';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'AM') {
                        $lista_modalidad_preponderante[] = 'AM';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'IN') {
                        $lista_modalidad_preponderante[] = 'IN';

                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'NA' || $buscar_primer_informe->modalidad == 'N/A') {
                        $lista_modalidad_preponderante[] = 'NA';
                    }
                }
                //Buscamos en el tercer informe:
                $buscar_tercer_informe = TercerInforme::select('modalidad')->where('id_alumno', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
                if ($buscar_tercer_informe) {
                    if ($buscar_primer_informe->modalidad == 'GR') {
                        $lista_modalidad_preponderante[] = 'GR';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'AM') {
                        $lista_modalidad_preponderante[] = 'AM';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'IN') {
                        $lista_modalidad_preponderante[] = 'IN';

                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'NA' || $buscar_primer_informe->modalidad == 'N/A') {
                        $lista_modalidad_preponderante[] = 'NA';
                    }
                }

                //Del array de modalidades, sacamos la que mas se repite:
                $modalidad_preponderante = array_reduce($lista_modalidad_preponderante, function ($carry, $item) {
                    $carry[$item] = ($carry[$item] ?? 0) + 1;
                    return $carry;
                }, []);

                //Validamos que tipo de modalidad fue:
                if (array_key_exists('GR', $modalidad_preponderante)) {
                    $atendidos_grupal_total++;
                }
                if (array_key_exists('IN', $modalidad_preponderante)) {
                    $atendidos_individual_total++;
                }
                if (array_key_exists('AM', $modalidad_preponderante)) {
                    $atendidos_ambas_total++;
                }
                if (array_key_exists('NA', $modalidad_preponderante)) {
                    $atendidos_NA_total++;
                }
                if (array_key_exists('N/A', $modalidad_preponderante)) {
                    $atendidos_NA_total++;
                }
            }
            //Tutorados atendidos:
            $informe_general->tutores_asignados_individual = $atendidos_individual_total;
            $informe_general->tutores_asignados_grupal = $atendidos_grupal_total;
            $informe_general->tutores_asignados_ambas = $atendidos_ambas_total;
            $informe_general->no_asistieron = $atendidos_NA_total;

            //Comprobamos que la suma de los 4 de arriba sea igual al total de tutorados asignados:
            $informe_general->comprobacion = $atendidos_individual_total + $atendidos_grupal_total + $atendidos_ambas_total + $atendidos_NA_total;

            //Verificamos las bajas y deserciones
            $informe_general->bajas = 0;
            $informe_general->desercion = 0;
            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                $buscar_primer_informe = Alumnos::select('mostrar')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();

                if ($buscar_primer_informe->mostrar == 0) {
                    $informe_general->bajas++;
                }
                if ($buscar_primer_informe->mostrar == 2) {
                    $informe_general->desercion++;
                }
            }

            //GRUPO Y SECCION:
            $informe_general->grupo_seccion = 'N/A';
            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                $buscar_primer_informe = Alumnos::select('grupo')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();

                $informe_general->grupo_seccion == $buscar_primer_informe->grupo;
            }


            $array_tutores_informacion[$i] = $informe_general;
        }

        // return $array_tutores_informacion;




        //=====================================================================
        // DISTRIBUCION POR SEMESTRE-SEXO
        $array_tutores_informacion_distribucion_sexo = array();
        //=====================================================================


        for ($i = 0; $i < $lista_tutorados_asignados_numero; $i++) {
            $distribucion_por_sexo_1 = new stdClass();
            $distribucion_por_sexo_1->numero_checador = $lista_tutorados_asignados[$i]->numero_checador;
            $distribucion_por_sexo_1->nombre_tutor = $lista_tutorados_asignados[$i]->nombre . " " . $lista_tutorados_asignados[$i]->apellido_paterno . " " . $lista_tutorados_asignados[$i]->apellido_materno;
            $lista_alumnos_del_tutor = ListadoTutorados::select('listado_tutorados.*')->where('id_tutor', $lista_tutorados_asignados[$i]->id_tutor)->get();


            $mujeres_primero = 0;
            $mujeres_distprimero = 0;
            $hombres_primero = 0;
            $hombres_distprimero = 0;

            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                //Buscamos en el primer informe:
                $informacion_alumno = Alumnos::select('*')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();
                if ($informacion_alumno) {
                    if ($informacion_alumno->sexo == 'M' && $informacion_alumno->semestre == 1) {
                        $mujeres_primero++;
                    }

                    if ($informacion_alumno->sexo == 'M' && $informacion_alumno->semestre > 1) {
                        $mujeres_distprimero++;
                    }
                    if ($informacion_alumno->sexo == 'H' && $informacion_alumno->semestre == 1) {
                        $hombres_primero++;
                    }

                    if ($informacion_alumno->sexo == 'H' && $informacion_alumno->semestre > 1) {
                        $hombres_distprimero++;
                    }
                }
            }
            //Tutorados atendidos:
            $distribucion_por_sexo_1->mujeres_primero = $mujeres_primero;
            $distribucion_por_sexo_1->mujeres_distprimero = $mujeres_distprimero;
            $distribucion_por_sexo_1->hombres_primero = $hombres_primero;
            $distribucion_por_sexo_1->hombres_distprimero = $hombres_distprimero;
            $distribucion_por_sexo_1->total = $mujeres_primero + $mujeres_distprimero + $hombres_primero + $hombres_distprimero;

            $array_tutores_informacion_distribucion_sexo[$i] = $distribucion_por_sexo_1;
        }

        //Sacamos el total de cada columna:
        $mujeres_primero_total = 0;
        $mujeres_distprimero_total = 0;
        $hombres_primero_total = 0;
        $hombres_distprimero_total = 0;

        //Del array array_tutores_informacion_distribucion_sexo hacemos la suma de todos los objetos:
        foreach ($array_tutores_informacion_distribucion_sexo as $objeto) {
            $mujeres_primero_total += $objeto->mujeres_primero;
            $mujeres_distprimero_total += $objeto->mujeres_distprimero;
            $hombres_primero_total += $objeto->hombres_primero;
            $hombres_distprimero_total += $objeto->hombres_distprimero;
        }

        //Ahora creamos un objeto para almacenar los totales:
        $distribucion_por_sexo_1_total = new stdClass();
        $distribucion_por_sexo_1_total->mujeres_primero_total = $mujeres_primero_total;
        $distribucion_por_sexo_1_total->mujeres_distprimero_total = $mujeres_distprimero_total;
        $distribucion_por_sexo_1_total->hombres_primero_total = $hombres_primero_total;
        $distribucion_por_sexo_1_total->hombres_distprimero_total = $hombres_distprimero_total;
        $distribucion_por_sexo_1_total->total = $mujeres_primero_total + $mujeres_distprimero_total + $hombres_primero_total + $hombres_distprimero_total;





        //=====================================================================
        // DISTRIBUCION POR SEMESTRE-SEXO 2
        $array_tutores_informacion_distribucion_sexo_2 = array();
        //=====================================================================


        for ($i = 0; $i < $lista_tutorados_asignados_numero; $i++) {
            $distribucion_por_sexo_2 = new stdClass();
            $distribucion_por_sexo_2->numero_checador = $lista_tutorados_asignados[$i]->numero_checador;
            $distribucion_por_sexo_2->nombre_tutor = $lista_tutorados_asignados[$i]->nombre . " " . $lista_tutorados_asignados[$i]->apellido_paterno . " " . $lista_tutorados_asignados[$i]->apellido_materno;
            $lista_alumnos_del_tutor = ListadoTutorados::select('listado_tutorados.*')->where('id_tutor', $lista_tutorados_asignados[$i]->id_tutor)->get();


            $semestre_1 = 0;
            $semestre_2 = 0;
            $semestre_3 = 0;
            $semestre_4 = 0;
            $semestre_5 = 0;
            $semestre_6 = 0;
            $semestre_7 = 0;
            $semestre_8 = 0;


            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                //Buscamos en el primer informe:
                $informacion_alumno = Alumnos::select('*')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();
                if ($informacion_alumno) {

                    if ($informacion_alumno->semestre == 1) {
                        $semestre_1++;
                    }
                    if ($informacion_alumno->semestre == 2) {
                        $semestre_2++;
                    }
                    if ($informacion_alumno->semestre == 3) {
                        $semestre_3++;
                    }
                    if ($informacion_alumno->semestre == 4) {
                        $semestre_4++;
                    }
                    if ($informacion_alumno->semestre == 5) {
                        $semestre_5++;
                    }
                    if ($informacion_alumno->semestre == 6) {
                        $semestre_6++;
                    }
                    if ($informacion_alumno->semestre == 7) {
                        $semestre_7++;
                    }
                    if ($informacion_alumno->semestre == 8) {
                        $semestre_8++;
                    }
                }
            }
            //Tutorados atendidos:
            $distribucion_por_sexo_2->semestre_1 = $semestre_1;
            $distribucion_por_sexo_2->semestre_2 = $semestre_2;
            $distribucion_por_sexo_2->semestre_3 = $semestre_3;
            $distribucion_por_sexo_2->semestre_4 = $semestre_4;
            $distribucion_por_sexo_2->semestre_5 = $semestre_5;
            $distribucion_por_sexo_2->semestre_6 = $semestre_6;
            $distribucion_por_sexo_2->semestre_7 = $semestre_7;
            $distribucion_por_sexo_2->semestre_8 = $semestre_8;
            $distribucion_por_sexo_2->total = $semestre_1 + $semestre_2 + $semestre_3 + $semestre_4 + $semestre_5 + $semestre_6 + $semestre_7 + $semestre_8;
            $array_tutores_informacion_distribucion_sexo_2[$i] = $distribucion_por_sexo_2;
        }

        //Hacemos la suma de todos los semestres del array:
        $semestre_1_total = 0;
        $semestre_2_total = 0;
        $semestre_3_total = 0;
        $semestre_4_total = 0;
        $semestre_5_total = 0;
        $semestre_6_total = 0;
        $semestre_7_total = 0;
        $semestre_8_total = 0;
 //Del array array_tutores_informacion_distribucion_sexo hacemos la suma de todos los objetos:
            foreach ($array_tutores_informacion_distribucion_sexo_2 as $objeto) {
                $semestre_1_total += $objeto->semestre_1;
                $semestre_2_total += $objeto->semestre_2;
                $semestre_3_total += $objeto->semestre_3;
                $semestre_4_total += $objeto->semestre_4;
                $semestre_5_total += $objeto->semestre_5;
                $semestre_6_total += $objeto->semestre_6;
                $semestre_7_total += $objeto->semestre_7;
                $semestre_8_total += $objeto->semestre_8;
            }

        $distribucion_por_sexo_2_total = new stdClass();
        $distribucion_por_sexo_2_total->semestre_1_total = $semestre_1_total;
        $distribucion_por_sexo_2_total->semestre_2_total = $semestre_2_total;
        $distribucion_por_sexo_2_total->semestre_3_total = $semestre_3_total;
        $distribucion_por_sexo_2_total->semestre_4_total = $semestre_4_total;
        $distribucion_por_sexo_2_total->semestre_5_total = $semestre_5_total;
        $distribucion_por_sexo_2_total->semestre_6_total = $semestre_6_total;
        $distribucion_por_sexo_2_total->semestre_7_total = $semestre_7_total;
        $distribucion_por_sexo_2_total->semestre_8_total = $semestre_8_total;
        $distribucion_por_sexo_2_total->total = $semestre_1_total + $semestre_2_total + $semestre_3_total + $semestre_4_total + $semestre_5_total + $semestre_6_total + $semestre_7_total + $semestre_8_total;



        //=====================================================================
        // RESULTADO, OBJETO DEVUELTO AL FRONTEND:
        //=====================================================================
        $numero_tutores_carrera_total = Tutores::select('*')
            ->join('docentes', 'docentes.id', '=', 'tutores.id_docente')
            ->where('id_carrera', $id_carrera)
            ->count();
        // $numero_tutores_carrera_total = ListadoTutorados::select('id_tutor')
        // ->join('tutores', 'tutores.id', '=', 'listado_tutorados.id_tutor')
        // ->where('id_generacion', $id_generacion)
        // ->distinct(['listado_tutorados.id_tutor'])
        // ->count();

        $numero_tutores_carrera_asignados = ListadoTutorados::select('id_tutor')->where('id_generacion', $id_generacion)->distinct(['listado_tutorados.id_tutor'])->count();

        // Crea un objeto personalizado para almacenar los valores
        $resultado = new stdClass();
        $resultado->lista_tutores_asignados = $lista_tutores_asignados;
        $resultado->lista_tutorados_asignados = $lista_tutorados_asignados;



        $resultado->valorMasRepetidoAsistencias = $valorMasRepetidoAsistencias;
        $resultado->valorMasRepetidoSesiones = $valorMasRepetidoSesiones;
        $resultado->valorMasRepetidoHoras = $valorMasRepetidoHoras;
        $resultado->valorMasRepetidoCanalizaciones = $valorMasRepetidoCanalizaciones;
        $resultado->valorMasRepetidoActividad = $valorMasRepetidoActividad;
        $resultado->valorMasRepetidoSituacion = $valorMasRepetidoSituacion;
        $resultado->valorMasRepetidoLogros = $valorMasRepetidoLogros;
        $resultado->sumaTotalAsistencias = $sumaTotalAsistencias;
        $resultado->sumaTotalSesiones = $sumaTotalSesiones;
        $resultado->sumaTotalHoras = $sumaTotalHoras;

        $resultado->total_tutores_asignados = $total_tutores_asignados;
        $resultado->numero_total_alumnos_asignados_carrera = $numero_total_alumnos_matricula_carrera;

        $resultado->numero_total_alumnos_con_diagnostico = $numero_total_alumnos_con_diagnostico;
        $resultado->porcentaje_alumnos_con_diagnostico = $porcentaje_alumnos_con_diagnostico;

        $resultado->numero_total_alumnos_atendidos_0 = $numero_total_alumnos_atendidos_0;
        $resultado->porcentaje_alumnos_atendidos_0 = $porcentaje_alumnos_atendidos_0;

        $resultado->numero_total_alumnos_atendidos_1 = $numero_total_alumnos_atendidos_1;
        $resultado->porcentaje_alumnos_atendidos_1 = $porcentaje_alumnos_atendidos_1;

        $resultado->numero_total_alumnos_atendidos_2 = $numero_total_alumnos_atendidos_2;
        $resultado->porcentaje_alumnos_atendidos_2 = $porcentaje_alumnos_atendidos_2;

        $resultado->numero_total_alumnos_atendidos_3 = $numero_total_alumnos_atendidos_3;
        $resultado->porcentaje_alumnos_atendidos_3 = $porcentaje_alumnos_atendidos_3;


        $resultado->alumnos_atendidos_forma_grupal_total = $alumnos_atendidos_forma_grupal_total;
        $resultado->porcentaje_alumnos_atendidos_forma_grupal = $porcentaje_alumnos_atendidos_forma_grupal;

        $resultado->alumnos_atendidos_forma_individual_total = $alumnos_atendidos_forma_individual_total;
        $resultado->porcentaje_alumnos_atendidos_forma_individual = $porcentaje_alumnos_atendidos_forma_individual;

        $resultado->alumnos_atendidos_forma_ambas_total = $alumnos_atendidos_forma_ambas_total;
        $resultado->porcentaje_alumnos_atendidos_forma_ambas = $porcentaje_alumnos_atendidos_forma_ambas;


        $resultado->alumnos_atendidos_forma_inasistencia = $alumnos_atendidos_forma_inasistencia;
        $resultado->alumnos_atendidos_forma_inasistencia_porcentaje = $alumnos_atendidos_forma_inasistencia_porcentaje;


        $resultado->alumnos_atendidos_piscologia = $alumnos_atendidos_piscologia;
        $resultado->porcentaje_alumnos_atendidos_piscologia = $porcentaje_alumnos_atendidos_piscologia;


        $resultado->alumnos_atendidos_instancias_extras = $alumnos_atendidos_instancias_extras;
        $resultado->porcentaje_alumnos_atendidos_instancias_extras = $porcentaje_alumnos_atendidos_instancias_extras;


        $resultado->alumnos_atendidos_academica = $alumnos_atendidos_academica;
        $resultado->porcentaje_alumnos_atendidos_academica = $porcentaje_alumnos_atendidos_academica;


        $resultado->alumnos_atendidos_salud = $alumnos_atendidos_salud;
        $resultado->porcentaje_alumnos_atendidos_salud = $porcentaje_alumnos_atendidos_salud;


        $resultado->alumnos_atendidos_becas = $alumnos_atendidos_becas;
        $resultado->porcentaje_alumnos_atendidos_becas = $porcentaje_alumnos_atendidos_becas;


        $resultado->alumnos_atendidos_otras = $alumnos_atendidos_otras;
        $resultado->porcentaje_alumnos_atendidos_otras = $porcentaje_alumnos_atendidos_otras;


        $resultado->total_matricula_carrera = $numero_total_alumnos_matricula_carrera;
        $resultado->porcentaje_total_matricula_carrera = ($numero_total_alumnos_matricula_carrera / $numero_total_alumnos_matricula_carrera) * 100;

        $resultado->informe_general = $array_tutores_informacion;
        $resultado->distribucion_por_sexo_1 = $array_tutores_informacion_distribucion_sexo;
        $resultado->distribucion_por_sexo_1_total = $distribucion_por_sexo_1_total;
        $resultado->distribucion_por_sexo_2 = $array_tutores_informacion_distribucion_sexo_2;
        $resultado->distribucion_por_sexo_2_total = $distribucion_por_sexo_2_total;


        $resultado->numero_tutores_carrera_total = $numero_tutores_carrera_total;
        $resultado->numero_tutores_carrera_asignados = $numero_tutores_carrera_asignados;
        // $resultado->porcentaje_tutores_carrera_asignados = ($numero_tutores_carrera_asignados / $numero_tutores_carrera_total) * 100;
        $resultado->porcentaje_tutores_carrera_asignados = ($total_tutores_asignados / $numero_tutores_carrera_total) * 100;



        // Devuelve el objeto como resultado
        // return response()->json($resultado);

        return response()->json([
            'codigo' => 1,
            'data' => $resultado
        ], 200);




        //Primero verificamos que la carrera , generacion y periodo ya pueda generar el reporte:


    }

    public function generarReporteParcial(Request $request)
    {

        $id_carrera = $request->input('id_carrera');
        $id_generacion = $request->input('id_generacion');
        $id_periodo = $request->input('id_periodo');
        $periodo = $request->input('periodo');

        //=========================================================================
        //=========================================================================
        //=========================================================================

        $lista_alumnos = ListadoTutorados::select('listado_tutorados.*', 'alumnos.id', 'alumnos.id_carrera', 'alumnos.id_generacion as ALUMNO_id_generacion')
            ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
            ->where('listado_tutorados.id_generacion', $id_generacion) // Especificamos la tabla
            ->where('alumnos.id_carrera', $id_carrera)
            ->where('alumnos.id_generacion', $id_generacion) // También especificamos la tabla
            ->get();

        $numero_total_alumnos_matricula_carrera = $lista_alumnos->count();

         //=========================================================================
        //=========================================================================
        //=========================================================================

        //SE AMPLIO LA CONSULTA DE ALUMNOS CON DIAGNOSTICO AGREGANDO EL ID DE CARRERA
        $numero_total_alumnos_con_diagnostico = PrimerInforme::select('primer_informe.*', 'registro_diagnostico')
        ->join('alumnos', 'alumnos.id', '=', 'primer_informe.id_alumno')
        ->where('primer_informe.id_generacion', $id_generacion)
        ->where('primer_informe.periodo', $periodo)
        ->where('alumnos.id_carrera', $id_carrera)
        ->where('alumnos.id_generacion', $id_generacion)
        ->get();
        
        $numero_total_alumnos_con_diagnostico = $numero_total_alumnos_con_diagnostico->filter(function ($item) {
            return $item->registro_diagnostico == 'SI'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $numero_total_alumnos_con_diagnostico = $numero_total_alumnos_con_diagnostico->count();
    
        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_con_diagnostico = ($numero_total_alumnos_con_diagnostico*100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_con_diagnostico = 0; // O manejarlo de otra manera según tus necesidades
        }

        ///////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////
        //ALUMNOS ATENDIDOS GRUPAL, INDIVIDUAL, AMBAS

        $alumnos_atendidos_forma_grupal = reporte_semestral_individual::select('modalidadPrep')->where('id_generacion', $id_generacion)->where('periodo', $periodo)
            ->get();
        $alumnos_atendidos_forma_grupal = $alumnos_atendidos_forma_grupal->filter(function ($item) {
            return $item->modalidadPrep == 'GR'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $alumnos_atendidos_forma_grupal_total = $alumnos_atendidos_forma_grupal->count();


        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_forma_grupal = ($alumnos_atendidos_forma_grupal_total * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_forma_grupal = 0; // O manejarlo de otra manera según tus necesidades
        }


        //=========================================================================
        //=========================================================================
        //=========================================================================



        $alumnos_atendidos_forma_individual = reporte_semestral_individual::select('modalidadPrep')->where('id_generacion', $id_generacion)->where('periodo', $periodo)
            ->get();
        $alumnos_atendidos_forma_individual = $alumnos_atendidos_forma_individual->filter(function ($item) {
            return $item->modalidadPrep == 'IN'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $alumnos_atendidos_forma_individual_total = $alumnos_atendidos_forma_individual->count();

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_forma_individual = ($alumnos_atendidos_forma_individual_total * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_forma_individual = 0; // O manejarlo de otra manera según tus necesidades
        }

        //=========================================================================
        //=========================================================================
        //=========================================================================


        $alumnos_atendidos_forma_ambas = reporte_semestral_individual::select('modalidadPrep')->where('id_generacion', $id_generacion)->where('periodo', $periodo)
            ->get();
        $alumnos_atendidos_forma_ambas = $alumnos_atendidos_forma_ambas->filter(function ($item) {
            return $item->modalidadPrep == 'AM'; // Reemplaza 'tu_condicion' con el nombre del campo correcto
        });
        $alumnos_atendidos_forma_ambas_total = $alumnos_atendidos_forma_ambas->count();

        if ($numero_total_alumnos_asignados_carrera != 0) {
            $porcentaje_alumnos_atendidos_forma_ambas = ($alumnos_atendidos_forma_ambas_total * 100) / $numero_total_alumnos_asignados_carrera;
        } else {
            $porcentaje_alumnos_atendidos_forma_ambas = 0; // O manejarlo de otra manera según tus necesidades
        }

        //==========================================================================
        //==========================================================================
        //=============================================================================

        //Obtenemos la lista de tutores asignados, que usaremos para todas las demas consultas:
        $lista_tutores_asignados = ListadoTutorados::select('listado_tutorados.id_tutor', 'tutores.*', 'docentes.*')
            ->join('tutores', 'tutores.id', '=', 'listado_tutorados.id_tutor')
            ->join('docentes', 'docentes.id', '=', 'tutores.id_docente')
            ->where('listado_tutorados.id_generacion', $id_generacion)
            ->distinct('listado_tutorados.id_tutor') // Aplica distinct al campo id_tutor
            ->get();

        //==================================================================================

        $array_tutores_informacion = array();

        //Por cada tutor en "$lista_tutores_asignados" vamos a encontrar los tutoradops asignados:
        $lista_tutorados_asignados = ListadoTutorados::select('listado_tutorados.id_tutor', 'tutores.*', 'docentes.*')
            ->join('tutores', 'tutores.id', '=', 'listado_tutorados.id_tutor')
            ->join('docentes', 'docentes.id', '=', 'tutores.id_docente')
            ->where('listado_tutorados.id_generacion', $id_generacion)
            ->where('docentes.id_carrera', $id_carrera)
            ->distinct(['listado_tutorados.id_alumno', 'listado_tutorados.id_generacion']) // Aplica distinct al campo id_tutor
            ->get();


        //Recorremos segun los tutores:
        for ($i = 0; $i < $lista_tutorados_asignados_numero; $i++) {
            $informe_general = new stdClass();
            $informe_general->numero_checador = $lista_tutorados_asignados[$i]->numero_checador;
            $informe_general->nombre_tutor = $lista_tutorados_asignados[$i]->nombre . " " . $lista_tutorados_asignados[$i]->apellido_paterno . " " . $lista_tutorados_asignados[$i]->apellido_materno;
            $lista_alumnos_del_tutor = ListadoTutorados::select('listado_tutorados.*')->where('id_tutor', $lista_tutorados_asignados[$i]->id_tutor)->get();
            $informe_general->lista_alumnos_del_tutor = $lista_alumnos_del_tutor;
            $informe_general->tutorados_asignados = $lista_alumnos_del_tutor->count();

        
            $atendidos_grupal_total = 0;
            $atendidos_individual_total = 0;
            $atendidos_ambas_total = 0;
            $atendidos_NA_total = 0;

            //Por cada id del array de alumnos, buscamos en el primer,segundo y tercer informe, pero debemos para cuando lo encuentre e ir sumando:
            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                //Buscamos en el primer informe:
                $buscar_primer_informe = PrimerInforme::select('modalidad')->where('id_alumno', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
                if ($buscar_primer_informe) {
                    if ($buscar_primer_informe->modalidad == 'GR') {
                        $lista_modalidad_preponderante[] = 'GR';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'AM') {
                        $lista_modalidad_preponderante[] = 'AM';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'IN') {
                        $lista_modalidad_preponderante[] = 'IN';

                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'NA' || $buscar_primer_informe->modalidad == 'N/A') {
                        $lista_modalidad_preponderante[] = 'NA';
                    }
                }
                //Buscamos en el segundo informe:
                $buscar_segundo_informe = SegundoInforme::select('modalidad')->where('id_alumno', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
                if ($buscar_segundo_informe) {
                    if ($buscar_primer_informe->modalidad == 'GR') {
                        $lista_modalidad_preponderante[] = 'GR';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'AM') {
                        $lista_modalidad_preponderante[] = 'AM';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'IN') {
                        $lista_modalidad_preponderante[] = 'IN';

                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'NA' || $buscar_primer_informe->modalidad == 'N/A') {
                        $lista_modalidad_preponderante[] = 'NA';
                    }
                }
                //Buscamos en el tercer informe:
                $buscar_tercer_informe = TercerInforme::select('modalidad')->where('id_alumno', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->where('periodo', $periodo)->first();
                if ($buscar_tercer_informe) {
                    if ($buscar_primer_informe->modalidad == 'GR') {
                        $lista_modalidad_preponderante[] = 'GR';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'AM') {
                        $lista_modalidad_preponderante[] = 'AM';
                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'IN') {
                        $lista_modalidad_preponderante[] = 'IN';

                        // continue;
                    }
                    if ($buscar_primer_informe->modalidad == 'NA' || $buscar_primer_informe->modalidad == 'N/A') {
                        $lista_modalidad_preponderante[] = 'NA';
                    }
                }  

                //Del array de modalidades, sacamos la que mas se repite:
                $modalidad_preponderante = array_reduce($lista_modalidad_preponderante, function ($carry, $item) {
                    $carry[$item] = ($carry[$item] ?? 0) + 1;
                    return $carry;
                }, []);

                //Validamos que tipo de modalidad fue:
                if (array_key_exists('GR', $modalidad_preponderante)) {
                    $atendidos_grupal_total++;
                }
                if (array_key_exists('IN', $modalidad_preponderante)) {
                    $atendidos_individual_total++;
                }
                if (array_key_exists('AM', $modalidad_preponderante)) {
                    $atendidos_ambas_total++;
                }
                if (array_key_exists('NA', $modalidad_preponderante)) {
                    $atendidos_NA_total++;
                }
                if (array_key_exists('N/A', $modalidad_preponderante)) {
                    $atendidos_NA_total++;
                }
            }
            //Tutorados atendidos:
            $informe_general->tutores_asignados_individual = $atendidos_individual_total;
            $informe_general->tutores_asignados_grupal = $atendidos_grupal_total;
            $informe_general->tutores_asignados_ambas = $atendidos_ambas_total;
            $informe_general->no_asistieron = $atendidos_NA_total;

            //Verificamos las bajas y deserciones
            $informe_general->bajas = 0;
            $informe_general->desercion = 0;
            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                $buscar_primer_informe = Alumnos::select('mostrar')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();

                if ($buscar_primer_informe->mostrar == 0) {
                    $informe_general->bajas++;
                }
                if ($buscar_primer_informe->mostrar == 2) {
                    $informe_general->desercion++;
                }
            }

            //GRUPO Y SECCION:
            $informe_general->grupo_seccion = 'N/A';
            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                $buscar_primer_informe = Alumnos::select('grupo')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();

                $informe_general->grupo_seccion == $buscar_primer_informe->grupo;
            }


            $array_tutores_informacion[$i] = $informe_general;
        }


        //=====================================================================
        // DISTRIBUCION POR SEMESTRE-SEXO
        $array_tutores_informacion_distribucion_sexo = array();
        //=====================================================================


        for ($i = 0; $i < $lista_tutorados_asignados_numero; $i++) {
            $distribucion_por_sexo_1 = new stdClass();
            $distribucion_por_sexo_1->numero_checador = $lista_tutorados_asignados[$i]->numero_checador;
            $distribucion_por_sexo_1->nombre_tutor = $lista_tutorados_asignados[$i]->nombre . " " . $lista_tutorados_asignados[$i]->apellido_paterno . " " . $lista_tutorados_asignados[$i]->apellido_materno;
            $lista_alumnos_del_tutor = ListadoTutorados::select('listado_tutorados.*')->where('id_tutor', $lista_tutorados_asignados[$i]->id_tutor)->get();


            $mujeres_primero = 0;
            $mujeres_distprimero = 0;
            $hombres_primero = 0;
            $hombres_distprimero = 0;

            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                //Buscamos en el primer informe:
                $informacion_alumno = Alumnos::select('*')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();
                if ($informacion_alumno) {
                    if ($informacion_alumno->sexo == 'M' && $informacion_alumno->semestre == 1) {
                        $mujeres_primero++;
                    }

                    if ($informacion_alumno->sexo == 'M' && $informacion_alumno->semestre > 1) {
                        $mujeres_distprimero++;
                    }
                    if ($informacion_alumno->sexo == 'H' && $informacion_alumno->semestre == 1) {
                        $hombres_primero++;
                    }

                    if ($informacion_alumno->sexo == 'H' && $informacion_alumno->semestre > 1) {
                        $hombres_distprimero++;
                    }
                }
            }
            //Tutorados atendidos:
            $distribucion_por_sexo_1->mujeres_primero = $mujeres_primero;
            $distribucion_por_sexo_1->mujeres_distprimero = $mujeres_distprimero;
            $distribucion_por_sexo_1->hombres_primero = $hombres_primero;
            $distribucion_por_sexo_1->hombres_distprimero = $hombres_distprimero;
            $distribucion_por_sexo_1->total = $mujeres_primero + $mujeres_distprimero + $hombres_primero + $hombres_distprimero;

            $array_tutores_informacion_distribucion_sexo[$i] = $distribucion_por_sexo_1;
        }

        //Sacamos el total de cada columna:
        $mujeres_primero_total = 0;
        $mujeres_distprimero_total = 0;
        $hombres_primero_total = 0;
        $hombres_distprimero_total = 0;

        //Del array array_tutores_informacion_distribucion_sexo hacemos la suma de todos los objetos:
        foreach ($array_tutores_informacion_distribucion_sexo as $objeto) {
            $mujeres_primero_total += $objeto->mujeres_primero;
            $mujeres_distprimero_total += $objeto->mujeres_distprimero;
            $hombres_primero_total += $objeto->hombres_primero;
            $hombres_distprimero_total += $objeto->hombres_distprimero;
        }

        //Ahora creamos un objeto para almacenar los totales:
        $distribucion_por_sexo_1_total = new stdClass();
        $distribucion_por_sexo_1_total->mujeres_primero_total = $mujeres_primero_total;
        $distribucion_por_sexo_1_total->mujeres_distprimero_total = $mujeres_distprimero_total;
        $distribucion_por_sexo_1_total->hombres_primero_total = $hombres_primero_total;
        $distribucion_por_sexo_1_total->hombres_distprimero_total = $hombres_distprimero_total;
        $distribucion_por_sexo_1_total->total = $mujeres_primero_total + $mujeres_distprimero_total + $hombres_primero_total + $hombres_distprimero_total;





        //=====================================================================
        // DISTRIBUCION POR SEMESTRE-SEXO 2
        $array_tutores_informacion_distribucion_sexo_2 = array();
        //=====================================================================


        for ($i = 0; $i < $lista_tutorados_asignados_numero; $i++) {
            $distribucion_por_sexo_2 = new stdClass();
            $distribucion_por_sexo_2->numero_checador = $lista_tutorados_asignados[$i]->numero_checador;
            $distribucion_por_sexo_2->nombre_tutor = $lista_tutorados_asignados[$i]->nombre . " " . $lista_tutorados_asignados[$i]->apellido_paterno . " " . $lista_tutorados_asignados[$i]->apellido_materno;
            $lista_alumnos_del_tutor = ListadoTutorados::select('listado_tutorados.*')->where('id_tutor', $lista_tutorados_asignados[$i]->id_tutor)->get();


            $semestre_1 = 0;
            $semestre_2 = 0;
            $semestre_3 = 0;
            $semestre_4 = 0;
            $semestre_5 = 0;
            $semestre_6 = 0;
            $semestre_7 = 0;
            $semestre_8 = 0;


            foreach ($lista_alumnos_del_tutor as $id_alumno) {
                $lista_modalidad_preponderante = [];
                //Buscamos en el primer informe:
                $informacion_alumno = Alumnos::select('*')->where('id', $id_alumno->id_alumno)->where('id_generacion', $id_generacion)->first();
                if ($informacion_alumno) {

                    if ($informacion_alumno->semestre == 1) {
                        $semestre_1++;
                    }
                    if ($informacion_alumno->semestre == 2) {
                        $semestre_2++;
                    }
                    if ($informacion_alumno->semestre == 3) {
                        $semestre_3++;
                    }
                    if ($informacion_alumno->semestre == 4) {
                        $semestre_4++;
                    }
                    if ($informacion_alumno->semestre == 5) {
                        $semestre_5++;
                    }
                    if ($informacion_alumno->semestre == 6) {
                        $semestre_6++;
                    }
                    if ($informacion_alumno->semestre == 7) {
                        $semestre_7++;
                    }
                    if ($informacion_alumno->semestre == 8) {
                        $semestre_8++;
                    }
                }
            }
            //Tutorados atendidos:
            $distribucion_por_sexo_2->semestre_1 = $semestre_1;
            $distribucion_por_sexo_2->semestre_2 = $semestre_2;
            $distribucion_por_sexo_2->semestre_3 = $semestre_3;
            $distribucion_por_sexo_2->semestre_4 = $semestre_4;
            $distribucion_por_sexo_2->semestre_5 = $semestre_5;
            $distribucion_por_sexo_2->semestre_6 = $semestre_6;
            $distribucion_por_sexo_2->semestre_7 = $semestre_7;
            $distribucion_por_sexo_2->semestre_8 = $semestre_8;
            $distribucion_por_sexo_2->total = $semestre_1 + $semestre_2 + $semestre_3 + $semestre_4 + $semestre_5 + $semestre_6 + $semestre_7 + $semestre_8;
            $array_tutores_informacion_distribucion_sexo_2[$i] = $distribucion_por_sexo_2;
        }

        //Hacemos la suma de todos los semestres del array:
        $semestre_1_total = 0;
        $semestre_2_total = 0;
        $semestre_3_total = 0;
        $semestre_4_total = 0;
        $semestre_5_total = 0;
        $semestre_6_total = 0;
        $semestre_7_total = 0;
        $semestre_8_total = 0;
 //Del array array_tutores_informacion_distribucion_sexo hacemos la suma de todos los objetos:
            foreach ($array_tutores_informacion_distribucion_sexo_2 as $objeto) {
                $semestre_1_total += $objeto->semestre_1;
                $semestre_2_total += $objeto->semestre_2;
                $semestre_3_total += $objeto->semestre_3;
                $semestre_4_total += $objeto->semestre_4;
                $semestre_5_total += $objeto->semestre_5;
                $semestre_6_total += $objeto->semestre_6;
                $semestre_7_total += $objeto->semestre_7;
                $semestre_8_total += $objeto->semestre_8;
            }

        $distribucion_por_sexo_2_total = new stdClass();
        $distribucion_por_sexo_2_total->semestre_1_total = $semestre_1_total;
        $distribucion_por_sexo_2_total->semestre_2_total = $semestre_2_total;
        $distribucion_por_sexo_2_total->semestre_3_total = $semestre_3_total;
        $distribucion_por_sexo_2_total->semestre_4_total = $semestre_4_total;
        $distribucion_por_sexo_2_total->semestre_5_total = $semestre_5_total;
        $distribucion_por_sexo_2_total->semestre_6_total = $semestre_6_total;
        $distribucion_por_sexo_2_total->semestre_7_total = $semestre_7_total;
        $distribucion_por_sexo_2_total->semestre_8_total = $semestre_8_total;
        $distribucion_por_sexo_2_total->total = $semestre_1_total + $semestre_2_total + $semestre_3_total + $semestre_4_total + $semestre_5_total + $semestre_6_total + $semestre_7_total + $semestre_8_total;

        




        //=====================================================================
        // RESULTADO, OBJETO DEVUELTO AL FRONTEND:
        //=====================================================================



        // Crea un objeto personalizado para almacenar los valores
        $resultado = new stdClass();
        $resultado->lista_tutores_asignados = $lista_tutores_asignados;
        $resultado->lista_tutorados_asignados = $lista_tutorados_asignados;

        $resultado->total_tutores_asignados = $total_tutores_asignados;
        $resultado->numero_total_alumnos_asignados_carrera = $numero_total_alumnos_matricula_carrera;

        $resultado->numero_total_alumnos_con_diagnostico = $numero_total_alumnos_con_diagnostico;
        //$resultado->porcentaje_alumnos_con_diagnostico = $porcentaje_alumnos_con_diagnostico;


        $resultado->alumnos_atendidos_forma_grupal_total = $alumnos_atendidos_forma_grupal_total;
        $resultado->porcentaje_alumnos_atendidos_forma_grupal = $porcentaje_alumnos_atendidos_forma_grupal;

        $resultado->alumnos_atendidos_forma_individual_total = $alumnos_atendidos_forma_individual_total;
        $resultado->porcentaje_alumnos_atendidos_forma_individual = $porcentaje_alumnos_atendidos_forma_individual;

        $resultado->alumnos_atendidos_forma_ambas_total = $alumnos_atendidos_forma_ambas_total;
        $resultado->porcentaje_alumnos_atendidos_forma_ambas = $porcentaje_alumnos_atendidos_forma_ambas;


        $resultado->alumnos_atendidos_forma_inasistencia = $alumnos_atendidos_forma_inasistencia;
        $resultado->alumnos_atendidos_forma_inasistencia_porcentaje = $alumnos_atendidos_forma_inasistencia_porcentaje;

        ///

        $resultado->total_matricula_carrera = $numero_total_alumnos_matricula_carrera;
        $resultado->porcentaje_total_matricula_carrera = ($numero_total_alumnos_matricula_carrera / $numero_total_alumnos_matricula_carrera) * 100;

        $resultado->informe_general = $array_tutores_informacion;
        $resultado->distribucion_por_sexo_1 = $array_tutores_informacion_distribucion_sexo;
        $resultado->distribucion_por_sexo_1_total = $distribucion_por_sexo_1_total;
        $resultado->distribucion_por_sexo_2 = $array_tutores_informacion_distribucion_sexo_2;
        $resultado->distribucion_por_sexo_2_total = $distribucion_por_sexo_2_total;


        $resultado->numero_tutores_carrera_total = $numero_tutores_carrera_total;
        $resultado->numero_tutores_carrera_asignados = $numero_tutores_carrera_asignados;
        // $resultado->porcentaje_tutores_carrera_asignados = ($numero_tutores_carrera_asignados / $numero_tutores_carrera_total) * 100;
        $resultado->porcentaje_tutores_carrera_asignados = ($total_tutores_asignados / $numero_tutores_carrera_total) * 100;



        // Devuelve el objeto como resultado
        // return response()->json($resultado);

        return response()->json([
            'codigo' => 1,
            'data' => $resultado
        ], 200);





    }

    public function verificacionInformesCoordinadorInst(Request $request)
    {
        //Es NECESARIO AGREGAR LA CARRERA EN ESTAS TABLAS PARA PODER FILTRAR TODO EL PINCHE PEDO.

        // $reportes_alumnos = reporte_semestral_individual::select('reporte_semestral_individual.*','alumnos.id','alumnos.id_carrera','alumnos.id_generacion','alumnos.id_periodo')
        //     ->join('alumnos', 'alumnos.id', '=', 'reporte_semestral_individual.id_alumno')
        //     ->where('id_generacion', $request->id_generacion)
        //     ->where('id_periodo', $request->id_periodo)
        //     ->where('id_carrera', 'alumnos.id_carrera')
        //     ->get();


        //     return $reportes_alumnos;


        // $id_carrera = $request->input('id_carrera');
        // $id_tutor = $request->input('id_tutor'); //NO
        $id_generacion = $request->input('id_generacion'); //SI 1
        $periodo = $request->input('periodo'); //SI 1
        $id_carrera = $request->input('id_carrera'); //SI 1

        $total_tutorados = 0;

        try {

            //ES NECESARIO BUSCAR LOS INFORMES QUE ESTEN EN ESTATUS 1, ES DECIR, QUE ESTEN CAPTURADOS.
            $tutorados_por_tutor = ListadoTutorados::select('*')
                ->join('alumnos', 'alumnos.id', '=', 'listado_tutorados.id_alumno')
                ->where('alumnos.id_generacion', $id_generacion)
                ->where('alumnos.id_carrera', $id_carrera)
                ->where('alumnos.mostrar', 1)
                // ->where('id_tutor', $id_tutor)
                ->get();

            //CONTAMOS EL NUMERO TOTAL DE TUTORADOS QUE TIENE EL TUTOR:
            $total_tutorados = count($tutorados_por_tutor); //2


            //TENEMOS QUE TENER SEGUN EL NUMERO DE TUTORADOS EN TOTAL, POR CADA INFORME (PRIMERO,SEGUNDO,TERCERO) QUE ESTEN YA CAPTURADOS TODOS.
            $primer_informe = PrimerInforme::select('*')
                ->join('alumnos', 'alumnos.id', '=', 'primer_informe.id_alumno')
                ->where('alumnos.id_generacion', $id_generacion)
                ->where('alumnos.id_carrera', $id_carrera)
                // ->where('id_generacion', $id_generacion)
                ->where('estatus_informe', 1)
                ->where('periodo', $periodo)
                ->get();

            $total_primer_informe_terminado = count($primer_informe);

            $segundo_informe = SegundoInforme::select('*')
            ->join('alumnos', 'alumnos.id', '=', 'segundo_informe.id_alumno')
                ->where('alumnos.id_generacion', $id_generacion)
                ->where('alumnos.id_carrera', $id_carrera)
                // ->where('id_generacion', $id_generacion)
                ->where('estatus_informe', 1)
                ->where('periodo', $periodo)

                ->get();

            $total_segundo_informe_terminado = count($segundo_informe);

            $tercer_informe = TercerInforme::select('*')
            ->join('alumnos', 'alumnos.id', '=', 'tercer_informe.id_alumno')
                ->where('alumnos.id_generacion', $id_generacion)
                ->where('alumnos.id_carrera', $id_carrera)
                // ->where('id_generacion', $id_generacion)
                ->where('estatus_informe', 1)
                ->where('periodo', $periodo)
                ->get();

            $total_tercer_informe_terminado = count($tercer_informe);


            $total_informes_terminados = $total_primer_informe_terminado + $total_segundo_informe_terminado + $total_tercer_informe_terminado;

                                                                                                                              //ERROR GENERACION DE INFORME SEMESTRAL              
            $pendientes = $total_tutorados >= $total_informes_terminados ? $total_tutorados - $total_informes_terminados : 0; //return ($total_informes_terminados);



            return response()->json([
                'codigo' => 1,
                'mensaje' => 'INFORMACION DE INFORMES',
                'PRIMERO' => [
                    'total_primer_informe_terminado' => $total_primer_informe_terminado,
                    'total_tutorados' => $total_tutorados,
                    'pendientes' => $total_tutorados - $total_primer_informe_terminado,
                    'estatus' => $total_primer_informe_terminado >= $total_tutorados ? 1 : 0,
                ],
                'SEGUNDO' => [
                    'total_segundo_informe_terminado' => $total_segundo_informe_terminado,
                    'total_tutorados' => $total_tutorados,
                    'pendientes' => $total_tutorados - $total_segundo_informe_terminado,
                    'estatus' => $total_segundo_informe_terminado >= $total_tutorados ? 1 : 0,
                ],
                'TERCERO' => [
                    'total_tercer_informe_terminado' => $total_tercer_informe_terminado,
                    'total_tutorados' => $total_tutorados,
                    'pendientes' => $total_tutorados - $total_tercer_informe_terminado,
                    'estatus' => $total_tercer_informe_terminado >= $total_tutorados ? 1 : 0,
                ],
                'TOTAL_INFORMES_TERMINADOS' => $total_informes_terminados,
                'PENDIENTES_TOTALES' => $pendientes,
                'ESTATUS' => $pendientes > 0 ? 0 : 1,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
