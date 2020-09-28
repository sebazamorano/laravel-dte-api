<?php

namespace App\Http\Controllers\API\V1;

use App\Repositories\AcceptanceClaimRepository;
use Response;
use App\Components\Sii;
use Illuminate\Http\Request;
use App\Http\Request\APIRequest;
use App\Models\SII\AcceptanceClaim;
use App\Models\SII\DocumentInformation;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateAcceptanceClaimAPIRequest;
use App\Http\Requests\API\UpdateAcceptanceClaimAPIRequest;

/**
 * Class AcceptanceClaimAPIController.
 */
class AcceptanceClaimAPIController extends AppBaseController
{
    /** @var AcceptanceClaimRepository */
    private $acceptanceClaimRepository;

    public function __construct(AcceptanceClaimRepository $acceptanceClaimRepo)
    {
        $this->acceptanceClaimRepository = $acceptanceClaimRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/acceptance_claims/company/{company_id}",
     *      summary="Get a listing of the acceptance_claims.",
     *      tags={"AcceptanceClaim"},
     *      description="Get all acceptance_claims",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="company_id",
     *          description="id of the company",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/AcceptanceClaim")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $acceptanceClaims = $this->acceptanceClaimRepository->all();

        return $this->sendResponse($acceptanceClaims->toArray(), 'Acceptance Claims retrieved successfully');
    }

    /**
     * @param CreateAcceptanceClaimAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/acceptance_claims/company/{company_id}",
     *      summary="Store a newly created acceptanceClaim in storage",
     *      tags={"AcceptanceClaim"},
     *      description="Store acceptanceClaim",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="company_id",
     *          description="id of the company",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="acceptanceClaim that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AcceptanceClaim")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/AcceptanceClaim"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAcceptanceClaimAPIRequest $request, $company_id)
    {
        /* @var $document_information DocumentInformation */
        $input = $request->all();

        $document_information = DocumentInformation::getModel($input);
        if ($document_information) {
            $date1 = new \DateTime($document_information->reception_date);
            $date2 = new \DateTime(date('Y-m-d H:i:s'));
            $interval = $date1->diff($date2);

            if ($interval->format('%a') < Sii::PlazoDiasRespuestaSII) {
                $result = AcceptanceClaim::saveActionSii($input);
                if (isset($result['codResp'])) {
                    if (in_array($result['codResp'], [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 19])) {
                        return $this->sendError($result['descResp']);
                    } else {
                        $input['response_code'] = $result['codResp'];
                        $input['response_description'] = $result['descResp'];
                        $input['event_date'] = date('Y-m-d H:i:s');
                        $acceptanceClaim = $this->acceptanceClaimRepository->create($input);
                    }
                }
            }
        } else {
            return $this->sendError('Error al ingresar información', 500);
        }

        return $this->sendResponse($acceptanceClaim->toArray(), 'Acceptance Claim saved successfully');
    }

    /**
     * @param APIRequest $request
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/acceptance_claims/{id}/company/{company_id}",
     *      summary="Display the specified acceptanceClaim",
     *      tags={"AcceptanceClaim"},
     *      description="Get acceptanceClaim",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of acceptanceClaim",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="company_id",
     *          description="id of the company",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/AcceptanceClaim"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show(APIRequest $request, $id, $company_id)
    {
        /** @var acceptanceClaim $acceptanceClaim */
        $acceptanceClaim = $this->acceptanceClaimRepository->findWithoutFail($id);

        if (empty($acceptanceClaim)) {
            return $this->sendError('Acceptance Claim not found');
        }

        return $this->sendResponse($acceptanceClaim->toArray(), 'Acceptance Claim retrieved successfully');
    }

    /**
     * @param int $id
     * @param int $company_id
     * @param UpdateAcceptanceClaimAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/acceptance_claims/{id}/company/{company_id}",
     *      summary="Update the specified acceptanceClaim in storage",
     *      tags={"AcceptanceClaim"},
     *      description="Update acceptanceClaim",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of acceptanceClaim",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="company_id",
     *          description="id of the company",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="acceptanceClaim that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/AcceptanceClaim")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/AcceptanceClaim"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, $company_id, UpdateAcceptanceClaimAPIRequest $request)
    {
        $input = $request->all();

        /** @var acceptanceClaim $acceptanceClaim */
        $acceptanceClaim = $this->acceptanceClaimRepository->findWithoutFail($id);

        if (empty($acceptanceClaim)) {
            return $this->sendError('Acceptance Claim not found');
        }

        $acceptanceClaim = $this->acceptanceClaimRepository->update($input, $id);

        return $this->sendResponse($acceptanceClaim->toArray(), 'acceptanceClaim updated successfully');
    }

    /**
     * @param APIRequest $request
     * @param int $id
     * @param int $company_id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/acceptance_claims/{id}/company/{company_id}",
     *      summary="Remove the specified acceptanceClaim from storage",
     *      tags={"AcceptanceClaim"},
     *      description="Delete acceptanceClaim",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of acceptanceClaim",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="company_id",
     *          description="id of the company",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy(APIRequest $request, $id, $company_id)
    {
        /** @var acceptanceClaim $acceptanceClaim */
        $acceptanceClaim = $this->acceptanceClaimRepository->findWithoutFail($id);

        if (empty($acceptanceClaim)) {
            return $this->sendError('Acceptance Claim not found');
        }

        $acceptanceClaim->delete();

        return $this->sendResponse($id, 'Acceptance Claim deleted successfully');
    }
}
