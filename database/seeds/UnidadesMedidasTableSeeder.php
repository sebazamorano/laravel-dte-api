<?php

use Carbon\Carbon;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadesMedidasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnidadMedida::insert([
            [
                'codigo' => 'KG', 'nombre' => 'Kilogramos', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'UND', 'nombre' => 'Unidad', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'DISP', 'nombre' => 'Display', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'PAQ', 'nombre' => 'Paquetes', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'CAJA', 'nombre' => 'Caja', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'HORA', 'nombre' => 'Hora', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
