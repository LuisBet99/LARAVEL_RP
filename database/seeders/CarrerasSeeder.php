<?php

namespace Database\Seeders;

use App\Models\Carreras;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarrerasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Aqui agregamos las carreras:
        Carreras::create([
            'nombre' => 'INGENIERIA INFORMATICA',
            'mostrar' => true,
        ]);
        Carreras::create([
            'nombre' => 'INGENIERIA EN GESTION EMPRESARIAL',
            'mostrar' => true,
        ]);


    }
}
