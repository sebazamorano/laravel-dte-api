<?php

use App\Models\Empleado;
use Illuminate\Database\Seeder;

class EmpleadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Quieres crear un empleado inicial ?')) {
            $empleado = Empleado::create([
                'empresa_id' => 1,
                'nombres' => 'lorem',
                'apellido_paterno' => 'ipsum',
                'apellido_materno' => 'ipsum',
                'admin' => false,
            ]);
        }
    }
}
