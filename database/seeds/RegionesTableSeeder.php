<?php

use Carbon\Carbon;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $connection = config('database.default');

        if ($connection == 'sqlsrv') {
            DB::statement('SET IDENTITY_INSERT regiones ON');
        }

        Region::create([
                    'id' => 1,
                    'pais_id' => 1,
                    'nombre' => 'Región de Tarapacá',
                    'ISO_3166_2_CL'=> 'CL-TA',
                ]);

        Region::create([
                    'id' => 2,
                    'pais_id' => 1,
                    'nombre' => 'Región de Antofagasta',
                    'ISO_3166_2_CL'=> 'CL-AN',
                ]);

        Region::create([
                    'id' => 3,
                    'pais_id' => 1,
                    'nombre' => 'Región de Atacama',
                    'ISO_3166_2_CL'=> 'CL-AT',
                ]);

        Region::create([
                    'id' => 4,
                    'pais_id' => 1,
                    'nombre' => 'Región de Coquimbo',
                    'ISO_3166_2_CL'=> 'CL-CO',
                ]);

        Region::create([
                    'id' => 5,
                    'pais_id' => 1,
                    'nombre' => 'Región de Valparaíso',
                    'ISO_3166_2_CL'=> 'CL-VS',
                ]);

        Region::create([
                    'id' => 6,
                    'pais_id' => 1,
                    'nombre' => 'Región del Libertador Gral. Bernardo O\'Higgins',
                    'ISO_3166_2_CL'=> 'CL-LI',
                ]);

        Region::create([
                    'id' => 7,
                    'pais_id' => 1,
                    'nombre' => 'Región del Maule',
                    'ISO_3166_2_CL'=> 'CL-ML',
                ]);

        Region::create([
                    'id' => 8,
                    'pais_id' => 1,
                    'nombre' => 'Región del Biobío',
                    'ISO_3166_2_CL'=> 'CL-BI',
                ]);

        Region::create([
                    'id' => 9,
                    'pais_id' => 1,
                    'nombre' => 'Región de la Araucanía',
                    'ISO_3166_2_CL'=> 'CL-AR',
                ]);

        Region::create([
                    'id' => 10,
                    'pais_id' => 1,
                    'nombre' => 'Región de Los Lagos',
                    'ISO_3166_2_CL'=> 'CL-LL',
                ]);

        Region::create([
                    'id' => 11,
                    'pais_id' => 1,
                    'nombre' => 'Región Aisén del Gral. Carlos Ibáñez del Campo',
                    'ISO_3166_2_CL'=> 'CL-AI',
                ]);

        Region::create([
                    'id' => 12,
                    'pais_id' => 1,
                    'nombre' => 'Región de Magallanes y de la Antártica Chilena',
                    'ISO_3166_2_CL'=> 'CL-MA',
                ]);

        Region::create([
                    'id' => 13,
                    'pais_id' => 1,
                    'nombre' => 'Región Metropolitana de Santiago',
                    'ISO_3166_2_CL'=> 'CL-RM',
                ]);

        Region::create([
                    'id' => 14,
                    'pais_id' => 1,
                    'nombre' => 'Región de Los Ríos',
                    'ISO_3166_2_CL'=> 'CL-LR',
                ]);

        Region::create([
                    'id' => 15,
                    'pais_id' => 1,
                    'nombre' => 'Arica y Parinacota',
                    'ISO_3166_2_CL'=> 'CL-AP',
                ]);

        if ($connection == 'sqlsrv') {
            DB::statement('SET IDENTITY_INSERT regiones OFF');
        }
    }
}
