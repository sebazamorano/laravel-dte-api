<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Repositories\RegionRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Http\Request\API\CreateRegionAPIRequest;
use App\Http\Request\API\UpdateRegionAPIRequest;
use App\Repositories\Criteria\LimitOffsetCriteria;

/**
 * Class RegionController.
 */
class RegionAPIController extends AppBaseController
{
    /** @var RegionRepository */
    private $regionRepository;

    public function __construct(RegionRepository $regionRepo)
    {
        $this->regionRepository = $regionRepo;
    }

    /**
     * Display a listing of the Region.
     * GET|HEAD /regiones.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->regionRepository->pushCriteria(new RequestCriteria($request));
        $this->regionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $regiones = $this->regionRepository->all();

        return $this->sendResponse($regiones->toArray(), 'Regiones retrieved successfully');
    }

    /**
     * Store a newly created Region in storage.
     * POST /regiones.
     *
     * @param CreateRegionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRegionAPIRequest $request)
    {
        $input = $request->all();

        $regiones = $this->regionRepository->create($input);

        return $this->sendResponse($regiones->toArray(), 'Region saved successfully');
    }

    /**
     * Display the specified Region.
     * GET|HEAD /regiones/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Region $region */
        $region = $this->regionRepository->findWithoutFail($id);

        if (empty($region)) {
            return $this->sendError('Region not found');
        }

        return $this->sendResponse($region->toArray(), 'Region retrieved successfully');
    }

    /**
     * Update the specified Region in storage.
     * PUT/PATCH /regiones/{id}.
     *
     * @param  int $id
     * @param UpdateRegionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRegionAPIRequest $request)
    {
        $input = $request->all();

        /** @var Region $region */
        $region = $this->regionRepository->findWithoutFail($id);

        if (empty($region)) {
            return $this->sendError('Region not found');
        }

        $region = $this->regionRepository->update($input, $id);

        return $this->sendResponse($region->toArray(), 'Region updated successfully');
    }

    /**
     * Remove the specified Region from storage.
     * DELETE /regiones/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Region $region */
        $region = $this->regionRepository->findWithoutFail($id);

        if (empty($region)) {
            return $this->sendError('Region not found');
        }

        $region->delete();

        return $this->sendResponse($id, 'Region deleted successfully');
    }
}
