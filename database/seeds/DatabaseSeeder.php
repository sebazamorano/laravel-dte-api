<?php

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
        // $this->call(UsersTableSeeder::class);
        $this->call(PaisTableSeeder::class);
        $this->call(RegionesTableSeeder::class);
        $this->call(ProvinciasTableSeeder::class);
        $this->call(ComunasTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeed::class);
        $this->call(MediosPagoTableSeeder::class);
        $this->call(ImpuestosTableSeeder::class);
        $this->call(TiposDocumentosTableSeeder::class);
        $this->call(ActividadesEconomicasTableSeeder::class);
        $this->call(BancosTableSeeder::class);
        $this->call(UnidadesMedidasTableSeeder::class);
    }
}
