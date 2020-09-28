<?php

use Carbon\Carbon;
use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisTableSeeder extends Seeder
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
            DB::statement('SET IDENTITY_INSERT pais ON');
        }

        Pais::Create([
            'id'=> 1,
            'codigoPais' => 'CL',
            'nombre' => 'CHILE',
        ]);

        if ($connection == 'sqlsrv') {
            DB::statement('SET IDENTITY_INSERT pais OFF');
        }
    }
}
