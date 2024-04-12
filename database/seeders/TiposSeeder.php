<?php

namespace Database\Seeders;

use App\Models\tipos_actividad_tutorial;
use App\Models\tipos_beca;
use App\Models\tipos_canalizacion;
use App\Models\tipos_canalizacion_becas;
use App\Models\tipos_logros;
use App\Models\tipos_modalidad;
use App\Models\tipos_situacion_problematica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Aqui vamos a crear todos los tipos que van a ser usados en el informe:


        // TIPOS DE BECA:
        tipos_beca::create([
            'nombre' => 'POR PROMEDIO',
            'abreviacion' => 'PR',
            'clave' => 'BM',
        ]);
        tipos_beca::create([
            'nombre' => 'SOCIOECONOMICA',
            'abreviacion' => 'SE',
            'clave' => 'BM',
        ]);
        tipos_beca::create([
            'nombre' => 'ALIMENTICIA',
            'abreviacion' => 'AL',
            'clave' => 'BM',
        ]);
        tipos_beca::create([
            'nombre' => 'BECA FEDERAL',
            'abreviacion' => 'BF',
            'clave' => 'BM',
        ]);
        tipos_beca::create([
            'nombre' => 'OTRA',
            'abreviacion' => 'OT',
            'clave' => 'BM',
        ]);
        tipos_beca::create([
            'nombre' => 'NO APLICA',
            'abreviacion' => 'NA',
            'clave' => 'BM',
        ]);

        // TIPOS DE ACTIVIDAD TUTORIAL:
        tipos_actividad_tutorial::create([
            'nombre' => 'CANALIZACION',
            'abreviacion' => 'CA',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'ASESORIA ACADEMICA',
            'abreviacion' => 'AC',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'CONSEJERIA',
            'abreviacion' => 'CO',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'TUTORIA INFORMATICA',
            'abreviacion' => 'TI',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'CURSO, TALLER, CONFERENCIA',
            'abreviacion' => 'CU',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'PRODUCTO ESCRITO',
            'abreviacion' => 'PE',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'CONVIVENCIA PADRES DE FAMILIA',
            'abreviacion' => 'CP',
            'clave' => 'AT',
        ]);
        tipos_actividad_tutorial::create([
            'nombre' => 'NINGUNA',
            'abreviacion' => 'NA',
            'clave' => 'AT',
        ]);



        // TIPOS DE MODALIDAD:

        tipos_modalidad::create([
            'nombre' => 'GRUPAL',
            'abreviacion' => 'GR',
            'clave' => 'MO',
        ]);
        tipos_modalidad::create([
            'nombre' => 'INDIVIDUAL',
            'abreviacion' => 'IN',
            'clave' => 'MO',
        ]);
        tipos_modalidad::create([
            'nombre' => 'AMBAS',
            'abreviacion' => 'AM',
            'clave' => 'MO',
        ]);


        // TIPOS DE SITUACION PROBLEMÁTICA:
        tipos_situacion_problematica::create([
            'nombre' => 'NINGUNO',
            'abreviacion' => 'NA',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'INTEGRACION',
            'abreviacion' => 'IN',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'SOCIAL',
            'abreviacion' => 'SO',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'FAMILIAR',
            'abreviacion' => 'FA',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'ECONOMICO',
            'abreviacion' => 'EC',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'ASESORIA',
            'abreviacion' => 'AS',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'REZAGO',
            'abreviacion' => 'RE',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'PERSONAL',
            'abreviacion' => 'PE',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'DESERCION POTENCIAL',
            'abreviacion' => 'DP',
            'clave' => 'SP',
        ]);
        tipos_situacion_problematica::create([
            'nombre' => 'BAJA',
            'abreviacion' => 'BA',
            'clave' => 'SP',
        ]);


        // TIPOS DE CANALIACION:
        tipos_canalizacion::create([
            'nombre' => 'SERVICIOS PSICOLOGICOS',
            'abreviacion' => 'SP',
            'clave' => 'CA',
        ]);
        tipos_canalizacion::create([
            'nombre' => 'SERVICIOS DE SALUD',
            'abreviacion' => 'SS',
            'clave' => 'CA',
        ]);
        tipos_canalizacion::create([
            'nombre' => 'ASESORIA ACADEMICA',
            'abreviacion' => 'AC',
            'clave' => 'CA',
        ]);
        tipos_canalizacion::create([
            'nombre' => 'INSTANCIAS EXTRAS',
            'abreviacion' => 'IE',
            'clave' => 'CA',
        ]);
        tipos_canalizacion::create([
            'nombre' => 'BECA',
            'abreviacion' => 'BE',
            'clave' => 'CA',
        ]);


        // TIPOS DE CANALIACION BECAS:

        tipos_canalizacion_becas::create([
            'nombre' => 'FEDERAL',
            'abreviacion' => 'FE',
            'clave' => 'CB',
        ]);
        tipos_canalizacion_becas::create([
            'nombre' => 'ALIMENTICIA',
            'abreviacion' => 'AL',
            'clave' => 'CB',
        ]);
        tipos_canalizacion_becas::create([
            'nombre' => 'SOCIIOECONOMICA',
            'abreviacion' => 'SO',
            'clave' => 'CB',
        ]);
        tipos_canalizacion_becas::create([
            'nombre' => 'ESFUERZO ACADEMICO',
            'abreviacion' => 'EA',
            'clave' => 'CB',
        ]);
        tipos_canalizacion_becas::create([
            'nombre' => 'EXCELENCIA ACADEMICA',
            'abreviacion' => 'EC',
            'clave' => 'CB',
        ]);
        tipos_canalizacion_becas::create([
            'nombre' => 'OTRA',
            'abreviacion' => 'OT',
            'clave' => 'CB',
        ]);


        //TIPOS LOGROS:

        tipos_logros::create([
            'nombre' => 'SUPERACION ACADEMICA',
            'abreviacion' => 'SA',
            'clave' => 'LO',
        ]);
        tipos_logros::create([
            'nombre' => 'SUPERACION PERSONAL',
            'abreviacion' => 'SP',
            'clave' => 'LO',
        ]);
        tipos_logros::create([
            'nombre' => 'SITUACION FAMILIAR',
            'abreviacion' => 'SF',
            'clave' => 'LO',
        ]);
        tipos_logros::create([
            'nombre' => 'NINGUNO',
            'abreviacion' => 'NA',
            'clave' => 'LO',
        ]);






    }
}
