<?php

namespace Database\Seeders;

use App\Models\Periodos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Periodos::create([
            'nombre_periodo' => 'PRIMERO',
        ]);
        Periodos::create([
            'nombre_periodo' => 'SEGUNDO',
        ]);
        Periodos::create([
            'nombre_periodo' => 'TERCERO',
        ]);
        Periodos::create([
            'nombre_periodo' => 'CUARTO',
        ]);
    }
}
