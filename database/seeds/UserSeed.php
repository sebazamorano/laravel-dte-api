<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'JOAQUIN GAMBOA FIGUEROA',
            'email' => 'joaquin.gamboa@dsmgroup.cl',
            'password' => bcrypt('password'),
            'admin' => true,
        ]);

        $this->command->info('Here is your admin details to login:');
        $this->command->warn($user->email);
        $this->command->warn('Password is "password"');
    }
}
