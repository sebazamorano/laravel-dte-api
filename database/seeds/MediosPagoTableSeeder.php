<?php

use App\Models\MedioPago;
use Illuminate\Database\Seeder;

class MediosPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedioPago::Create([
            'id'=> 1,
            'nombre' => 'Cheque',
            'valor' => 'CH',
        ]);

        MedioPago::Create([
            'id'=> 2,
            'nombre' => 'Cheque a fecha',
            'valor' => 'CF',
        ]);

        MedioPago::Create([
            'id'=> 3,
            'nombre' => 'Letra',
            'valor' => 'LT',
        ]);

        MedioPago::Create([
            'id'=> 4,
            'nombre' => 'Efectivo',
            'valor' => 'EF',
        ]);

        MedioPago::Create([
            'id'=> 5,
            'nombre' => 'Pago A Cta. Cte',
            'valor' => 'PE',
        ]);

        MedioPago::Create([
            'id'=> 6,
            'nombre' => 'Tarjeta Crédito',
            'valor' => 'TC',
        ]);

        MedioPago::Create([
            'id'=> 7,
            'nombre' => 'Otro',
            'valor' => 'OT',
        ]);
    }
}
