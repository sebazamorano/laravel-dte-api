<?php

use Carbon\Carbon;
use App\Models\Banco;
use Illuminate\Database\Seeder;

class BancosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banco::insert([
            [
                'codigo' => '001', 'nombre' => 'BANCO DE CHILE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '009', 'nombre' => 'BANCO INTERNACIONAL', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '014', 'nombre' => 'SCOTIABANK CHILE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '016', 'nombre' => 'BANCO DE CREDITO E INVERSIONES', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '027', 'nombre' => 'CORPBANCA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '028', 'nombre' => 'BANCO BICE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '031', 'nombre' => 'HSBC BANK (CHILE)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '037', 'nombre' => 'BANCO SANTANDER-CHILE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '039', 'nombre' => 'BANCO ITAÚ CHILE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '049', 'nombre' => 'BANCO SECURITY', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '051', 'nombre' => 'BANCO FALABELLA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '052', 'nombre' => 'DEUTSCHE BANK (CHILE)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '053', 'nombre' => 'BANCO RIPLEY', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '054', 'nombre' => 'RABOBANK CHILE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '055', 'nombre' => 'BANCO CONSORCIO', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '056', 'nombre' => 'BANCO PENTA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '057', 'nombre' => 'BANCO PARIS', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '504', 'nombre' => 'BANCO BILBAO VIZCAYA ARGENTARIA', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => '012', 'nombre' => 'BANCO DEL ESTADO DE CHILE', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),
            ],

        ]);
    }
}
