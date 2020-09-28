<?php

namespace App\Http\Controllers\API\V1;

use App\File;
use Response;
use App\Models\Caf;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\CafRepository;
use Illuminate\Support\Facades\Log;
use App\Components\CAF as CAFComponent;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateCafAPIRequest;
use App\Http\Requests\API\UpdateCafAPIRequest;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class CafController.
 */
class CafAPIController extends AppBaseController
{
    /** @var CafRepository */
    private $cafRepository;

    public function __construct(CafRepository $cafRepo)
    {
        $this->cafRepository = $cafRepo;
    }

    /**
     * Display a listing of the Caf.
     * GET|HEAD /cafs.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, $empresa_id)
    {
        $this->cafRepository->pushCriteria(new RequestCriteria($request));
        $this->cafRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cafs = $this->cafRepository->all();

        return $this->sendResponse($cafs->toArray(), 'Cafs retrieved successfully');
    }

    /**
     * Store a newly created Caf in storage.
     * POST /cafs.
     *
     * @param CreateCafAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCafAPIRequest $request, $empresa_id)
    {
        /* @var Empresa $empresa */
        $input = $request->all();

        $caf_data = CAFComponent::leerCAF($request->file('caf')->path());
        $empresa = Empresa::where('rut', $caf_data['rut'])->first();
        Caf::verificarEmpresa($empresa_id, $empresa);

        $autorizado = $empresa->documentoEstaAutorizado($caf_data['td']);

        if (! $autorizado) {
            throw new HttpResponseException(response()->json([
                'message' => '401 error',
                'errors' => ['td'=>['El tipo documento no esta autorizado.']],
                'status_code' => 401,
            ], JsonResponse::HTTP_UNAUTHORIZED));
        }

        Caf::validarUnique($caf_data);

        $fileUpload = File::uploadFileFromRequest($request, 'caf', 'caf');
        $input['file_id'] = $fileUpload->id;

        $input = Caf::prepararInformacionParaCrear($input, $caf_data);

        try {
            $caf = $this->cafRepository->create($input);

            if ($input['enUso'] == 1) {
                Caf::where('id', '!=', $caf->id)->where('empresa_id', $input['empresa_id'])->where('tipo_documento_id', $caf->tipo_documento_id)->update(['enUso' => 0]);
            }
        } catch (\Throwable $e) {
            Log::error('Error al subir folio '.$e->getMessage());

            throw new HttpResponseException(response()->json([
                'message' =>$e->getMessage(),
                'errors' => ['id'=>['Existio un error al procesar la información']],
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }

        return $this->sendResponse($caf->toArray(), 'Caf guardado exitosamente.');
    }

    /**
     * Display the specified Caf.
     * GET|HEAD /cafs/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($empresa_id, $id)
    {
        /** @var Caf $caf */
        $caf = $this->cafRepository->findWithoutFail($id);

        if (empty($caf)) {
            return $this->sendError('CAF no encontrado');
        }

        return $this->sendResponse($caf->toArray(), 'CAF recuperado exitosamente');
    }

    /**
     * Update the specified Caf in storage.
     * PUT/PATCH /cafs/{id}.
     *
     * @param  int $id
     * @param UpdateCafAPIRequest $request
     *
     * @return Response
     */
    public function update($empresa_id, $id, UpdateCafAPIRequest $request)
    {
        $input = $request->validated();

        /** @var Caf $caf */
        $caf = $this->cafRepository->findWithoutFail($id);

        if (empty($caf)) {
            return $this->sendError('CAF no encontrado');
        }

        $caf = $this->cafRepository->update($input, $id);

        return $this->sendResponse($caf->toArray(), 'CAF actualizado exitosamente');
    }

    /**
     * Remove the specified Caf from storage.
     * DELETE /cafs/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($empresa_id, $id)
    {
        /** @var Caf $caf */
        $caf = $this->cafRepository->findWithoutFail($id);

        if (empty($caf)) {
            return $this->sendError('CAF no encontrado');
        }

        $caf->delete();

        return $this->sendResponse($id, 'CAF borrado exitosamente');
    }
}
