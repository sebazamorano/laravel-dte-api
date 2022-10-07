<?php

namespace App;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Exceptions\HttpResponseException;


/**
 * Class File.
 * @version December 17, 2018, 1:09 pm UTC
 *
 * @property int empresa_id
 * @property string file_name
 * @property string mime_type
 * @property int file_size
 * @property string file_path
 * @property int status
 * @property string type
 */
class File extends Model
{
    /**
     * Define the columns which can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['empresa_id', 'file_name', 'mime_type', 'file_size', 'file_path', 'status', 'type'];

    public static function uploadEmailAttachment($content, $nombreArchivo, $mimeType, $fileSize = 0, $empresa_id = 0)
    {
        $fileNameS3 = uniqid().'_'.$nombreArchivo;
        if ($empresa_id != 0) {
            $empresa = Empresa::find($empresa_id);
            $path = "{$empresa->rut}/emails_attachments/".$fileNameS3;
        } else {
            $path = 'emails_attachments/'.$fileNameS3;
        }

        // make image entry to DB
        $file = self::create([
            'file_name' => $nombreArchivo,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_path' => $path,
            'type' => 's3',
        ]);

        Storage::cloud()->put($path, $content, 'private');

        $size = Storage::cloud()->size($path);

        $fileArk = self::find($file->id);
        $fileArk->status = 1;
        $fileArk->file_size = $size;

        if ($empresa_id != 0) {
            $fileArk->empresa_id = $empresa_id;
        }

        $fileArk->save();

        return $file;
    }

    public function uploadFileFromContent(Empresa $empresa, $content, $nombreArchivo, $mimeType, $fileSize = 0, $type = 'main')
    {
        $path = uniqid().'_'.$nombreArchivo;
        // upload image to S3
        Storage::cloud()->put("{$empresa->rut}/{$type}/".$path, $content, 'private');

        // make image entry to DB
        $file = self::create([
            'empresa_id' => $empresa->id,
            'file_name' => $nombreArchivo,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_path' => "{$empresa->rut}/{$type}/".$path,
            'type' => 's3',
        ]);

        $fileArk = self::find($file->id);
        $fileArk->status = 1;
        $fileArk->save();

        return $file;
    }

    public static function uploadFileFromRequest(Request $request, $nombreInput = '', $type = 'main', $company = null): self
    {
        /* @var Empresa $empresa */
        $file = $request->file($nombreInput);
        //$extension = $request->file('file')->guessExtension();
        $path = uniqid().'_'.$request->$nombreInput->getClientOriginalName();
        //$filename = uniqid() .  '.' . $extension;
        $mimeType = $request->file($nombreInput)->getClientMimeType();
        $fileSize = $request->file($nombreInput)->getClientSize();

        if($company == null){
            $empresa_id = $request->input('empresa_id');
            $empresa = Empresa::find($empresa_id);
        }else{
            $empresa = $company;
        }

        $nombreArchivo = $request->$nombreInput->getClientOriginalName();

        Storage::cloud()->put("{$empresa->rut}/{$type}/".$path, file_get_contents($file), 'private');
        // make image entry to DB
        $file = self::create([
            'empresa_id' => $empresa->id,
            'file_name' => $nombreArchivo,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_path' => "{$empresa->rut}/{$type}/".$path,
            'type' => 's3',
        ]);

        $fileArk = self::find($file->id);
        $fileArk->status = 1;
        $fileArk->save();

        return $file;
    }

    public function verificarArchivoExiste()
    {
        if (! Storage::cloud()->exists($this->file_path)) {
            return false;
        }

        return true;
    }

    public function obtenerLinkTemporal()
    {
        if (! self::verificarArchivoExiste()) {
            throw new HttpResponseException(response()->json([
                'message' => '422 error',
                'errors' => ['archivo'=>['El archivo no se encuentra disponible en el sistema de almacenamiento']],
                'status_code' => 422,
            ], JsonResponse::HTTP_BAD_REQUEST));
        }

        $tempUrl = Storage::cloud()->temporaryUrl($this->file_path, now()->addMinutes(5));

        return $tempUrl;
    }

    public function content()
    {
        return Storage::cloud()->get($this->file_path);
    }

    public static function store($array, $empresa, $type = 'main')
    {
        $file = self::create([
            'empresa_id' => $empresa->id,
            'mime_type' => $array['mime_type'],
            'file_size' => $array['file_size'],
            'file_name' => $array['file_name'],
            'file_path' => "{$empresa->rut}/{$type}/". $array['file_path'],
            'type' => 's3',
        ]);

        Storage::cloud()->put($file->file_path, $array['contents']);

        return $file;
    }

    public static function generateArray(Request $request, $input)
    {
        $file = $request->file($input);

        return [
            'contents' => file_get_contents($file->getRealPath()),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'file_name' => $file->getClientOriginalName(),
            'file_path' => uniqid() . '_' . $file->getClientOriginalName()
        ];
    }

    public function deleteSource()
    {
        Storage::cloud()->delete($this->file_path);
        self::delete();
    }
}
