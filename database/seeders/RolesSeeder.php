<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Roles::create([
            'nombre' => 'ALUMNO',
        ]);
        Roles::create([
            'nombre' => 'DOCENTE',
        ]);
        Roles::create([
            'nombre' => 'COORDINADOR_TUT',
        ]);
        Roles::create([
            'nombre' => 'COORDINADOR_INST',
        ]);
        Roles::create([
            'nombre' => 'DESARROLLO_ACADEMICO',
        ]);
    }
}
