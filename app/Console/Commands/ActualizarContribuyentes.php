<?php

namespace App\Console\Commands;

use App\Components\Sii;
use App\Models\Empresa;
use App\Models\Contribuyente;
use Illuminate\Console\Command;
use App\Models\CertificadoEmpresa;
use Illuminate\Support\Facades\Storage;

class ActualizarContribuyentes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:contribuyentes-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando utilizado para actualizar la tabla de contribuyentes desde el archivo SII';

    /** @var Sii */
    private $siiComponent;

    /**
     * Create a new command instance.
     *
     * @param Sii $siiComponent
     */
    public function __construct(Sii $siiComponent)
    {
        parent::__construct();
        $this->siiComponent = $siiComponent;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        /* @var Empresa $empresa */
        /* @var CertificadoEmpresa $certificado */
        $this->info('Proceso de Actualización de empresas');
        $this->info('Descargando certificado principal');
        $empresa = Empresa::find(1);
        $certificado = $empresa->certificados()->where('enUso', 1)->first();
        $contents = $certificado->pemFile->content();
        Storage::put('certificado.pem', $contents);

        $this->info('Iniciando Coneccion con Servidor Palena del SII...');
        $this->info('Descargando Archivo Csv con Listado Contribuyentes Electronicos...');

        $path_certificado = Storage::path('certificado.pem');
        $path_csv = Storage::path('intercambio.csv');
        $this->siiComponent->descargarListadoContribuyentes($path_csv, $path_certificado, $certificado->password);

        $this->info('Archivo descargado');

        Storage::delete('certificado.pem');

        $this->info('Leyendo CSV');
        $this->info('Este proceso puede tardar varios minutos, tenga paciencia...');

        $contribuyentes = Contribuyente::csvToArray();

        $this->info('CSV leido');
        $this->info('Se insertaran ' . count($contribuyentes) . ' registros');
        $this->info('Insertando registros porfavor espere...');
        Contribuyente::actualizarRegistros($contribuyentes);
        $this->info('Registros insertados');
        Storage::delete('intercambio.csv');
    }
}
