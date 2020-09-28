<?php

use App\Models\Impuesto;
use Illuminate\Database\Seeder;

class ImpuestosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Impuesto::Create([
            'nombre' => 'IVA ANTICIPADO FAENAMIENTO CARNE',
            'porcentaje' => 5.0,
            'codigo' => 17,
        ]);

        Impuesto::Create([
            'nombre' => 'IVA ANTICIPADO CARNE',
            'porcentaje' => 5.0,
            'codigo' => 18,
        ]);

        Impuesto::Create([
            'nombre' => 'IVA ANTICIPADO HARINA',
            'porcentaje' => 12.0,
            'codigo' => 19,
        ]);

        Impuesto::Create([
            'nombre' => 'DL 825/74 , ART. 42 a) Bebidas analcohólicas y minerales',
            'porcentaje' => 10.0,
            'codigo' => 27,
        ]);

        Impuesto::Create([
            'nombre' => 'DL 825/74 , ART. 42, letra c) Vinos',
            'porcentaje' => 20.5,
            'codigo' => 25,
        ]);

        Impuesto::Create([
            'nombre' => 'IVA Retenido Total',
            'porcentaje' => 19.0,
            'codigo' => 15,
        ]);

        Impuesto::Create([
            'nombre' => 'Impuesto Art. 42 a) Licores, Pisco, Destilados [F29 - C148]',
            'porcentaje' => 31.5,
            'codigo' => 24,
        ]);

        Impuesto::Create([
            'nombre' => 'Impuesto Art. 42 c) Cervezas y Bebidas Alcoholicas [F29 - C150]	',
            'porcentaje' => 20.5,
            'codigo' => 26,
        ]);

        Impuesto::Create([
            'nombre' => 'Bebidas analcohólicas y Minerales con elevado contenido de azúcares.',
            'porcentaje' => 18.0,
            'codigo' => 271,
        ]);
    }
}
