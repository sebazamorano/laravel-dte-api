<?php

use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class EmpresaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to seed Random Empresa information ?')) {
            $empresa = Empresa::create([
                'rut' => '76250838-9',
                'razonSocial' => 'ASESORIA DISENO E INGENIERIA SPA',
                'direccion' => '1 NORTE 461 OF 703',
                'region_id' => 5,
                'comuna_id' => 47,
                'provincia_id' => 14,
            ]);

            $sucursal = Sucursal::create([
                'empresa_id' => $empresa->id,
                'nombre' => 'CASA MATRIZ',
                'direccion' => '1 NORTE 461 OF 703',
                'region_id' => 5,
                'comuna_id' => 47,
                'provincia_id' => 14,
                'tipo' => 1,
            ]);
        }
    }
}
