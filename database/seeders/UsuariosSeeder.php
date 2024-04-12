<?php

namespace Database\Seeders;

use App\Models\CoordinadorInstitucional;
use App\Models\CoordinadorTutorias;
use App\Models\Docentes;
use App\Models\Tutores;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //USUARIO PARA DESARROLLO ACADEMICO:
        $user_desarrollo_academico=User::create([

            'name' => 'DESARROLLO ACADEMICO',
            'numero_checador' => '12345673',
            'password' => '12345673',
            'id_rol' => 5,
        ]);

        CoordinadorInstitucional::create([

            'nombre' => 'DESARROLLO ',
            'apellido_paterno' => 'ACADEMICO',
            'apellido_materno' => 'TEST',
            'numero_checador' => '12345673',
            'id_carrera' => 1,
            'mostrar' => 1,
            'id_usuario' => 1,
        ]);

        //CREAMOS EL USUARIO PARA COORDINADOR INSTITUCIONAL:
        $user_coordinador_inst =User::create([

            'name' => 'COORDINADOR INSTITUCIONAL',
            'numero_checador' => '12345674',
            'password' => '12345674',
            'id_rol' => 4,
        ]);
        CoordinadorInstitucional::create([

            'nombre' => 'COORDINADOR',
            'apellido_paterno' => 'INSTITUCIONAL',
            'apellido_materno' => 'TEST',
            'numero_checador' => '12345674',
            'id_carrera' => 1,
            'mostrar' => 1,
            'id_usuario' => 2,
        ]);

        //CREAMOS LOS DOS COORDINADORES DE TUTORIAS:
        User::create([

            'name' => 'ING. ROCIO LORENA RODRIGUEZ',
            'numero_checador' => '902',
            'password' => '902',
            'id_rol' => 3,
        ]);
        //Creamos el coordinador de tutorias:     protected $fillable = ['nombre','apellido_paterno','apellido_materno','numero_checador','id_carrera','mostrar','id_usuario',];

        CoordinadorTutorias::create([

            'nombre' => 'ROCIO LORENA RODRIGUEZ',
            'apellido_paterno' => 'RODRIGUEZ',
            'apellido_materno' => 'CHACON',
            'numero_checador' => '902',
            'id_carrera' => 1,
            'mostrar' => 1,
            'id_usuario' => 3,
        ]);

        User::create([

            'name' => 'TUTOR TEST',
            'numero_checador' => '12345675',
            'password' => '12345675',
            'id_rol' => 2,
        ]);

        Docentes::create([
            'nombre' => 'TUTOR',
            'apellido_paterno' => 'TEST',
            'apellido_materno' => '01',
            'numero_checador' => '12345675',
            'correo_institucional' => 'test_tutor@test.com',
            'id_carrera' => 1,
            'mostrar' => 1,
            'id_usuario' => 4,
        ]);




    }
}
