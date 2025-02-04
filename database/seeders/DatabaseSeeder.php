<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //Ejecutamos los seeders:
        $this->call(TiposSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(PeriodosSeeder::class);
        $this->call(CarrerasSeeder::class);
        $this->call(UsuariosSeeder::class);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
