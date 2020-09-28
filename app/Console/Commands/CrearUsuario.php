<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class CrearUsuario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuario:crear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creación de usuario';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Provea la información para crear un nuevo usuario.');
        $name = $this->value('Nombre usuario');
        $email = $this->value('Email usuario');

        if (! $this->confirmData($name, $email)) {
            $this->error('Proceso terminado.');

            return false;
        }

        $this->info('Creando usuario, porfavor espere...');
        $this->output->progressStart(1);

        $adminPassword = Str::random();
        $this->registrarUsuario($name, $adminPassword, $email);

        $this->output->progressAdvance();
        $this->output->progressFinish();
        $this->info("Usuario {$email} puede ingresar, usando contraseña: {$adminPassword}");
    }

    /**
     * @param $name
     * @return string
     */
    private function value($name)
    {
        $value = $this->ask("Porfavor ingrese {$name}");
        if (empty($value)) {
            $this->error("{$name} no puede ser vacio.");

            return $this->value($name);
        }

        return $value;
    }

    /**
     * @param $name
     * @param $email
     */
    private function confirmData($name, $email)
    {
        $this->info('Información del nuevo usuario');
        $this->info('------------');
        $this->info('');
        $this->info("Nombre usuario: {$name}");
        $this->info("Email Usuario: {$email}");
        if ($this->confirm('Quieres crear el usuario con estos datos?')) {
            return true;
        }

        return false;
    }

    public function registrarUsuario($name, $password, $email)
    {
        $user = User::create(['name' => $name, 'email' => $email, 'password' => bcrypt($password)]);

        return $user;
    }
}
