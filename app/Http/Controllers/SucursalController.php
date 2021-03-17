<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyBranchOfficeRequest;
use App\Http\Requests\UpdateCompanyBranchOfficeRequest;
use App\Models\Empresa as Company;
use App\Models\Sucursal as CompanyBranchOffice;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the CompanyBranchOffice.
     *
     * @param Company $company
     * @return Response
     */
    public function index(Company $company)
    {
        $companyBranchOffices = $company->companyBranchOffices;
        return view('company_branch_offices.index')
            ->with(['companyBranchOffices' => $companyBranchOffices, 'company' => $company]);
    }

    /**
     * Show the form for creating a new CompanyBranchOffice.
     *
     * @param Company $company
     * @return Response
     */
    public function create(Company $company)
    {
        return view('company_branch_offices.create')->with('company', $company);
    }

    /**
     * Store a newly created CompanyBranchOffice in storage.
     *
     * @param CreateCompanyBranchOfficeRequest $request
     * @param Company $company
     * @return Response
     */
    public function store(CreateCompanyBranchOfficeRequest $request, Company $company)
    {
        $input = $request->all();
        $input['company_id'] = $company->id;

        /** @var CompanyBranchOffice $companyBranchOffice */
        $companyBranchOffice = CompanyBranchOffice::create($input);

        request()->session()->flash('success-message', [__('messages.saved', ['model' => __('models/company_branch_offices.plural')])]);

        return redirect(route('companies.show', ['company' => $company->id]));
    }

    /**
     * Display the specified CompanyBranchOffice.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($company_id, $companyBranchOffice_id)
    {
        /** @var CompanyBranchOffice $companyBranchOffice */
        $companyBranchOffice = CompanyBranchOffice::find($companyBranchOffice_id);

        if (empty($companyBranchOffice)) {
            request()->session()->flash('error-message', [__('messages.not_found', ['model' => __('models/company_branch_offices.plural')])]);

            return redirect(route('companies.show', ['company' => $company_id]));
        }

        return view('company_branch_offices.show')->with('companyBranchOffice', $companyBranchOffice);
    }

    /**
     * Show the form for editing the specified CompanyBranchOffice.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($company_id, $companyBranchOffice_id)
    {
        /** @var CompanyBranchOffice $companyBranchOffice */
        $companyBranchOffice = CompanyBranchOffice::find($companyBranchOffice_id);

        if (empty($companyBranchOffice)) {
            request()->session()->flash('error-message', [__('messages.not_found', ['model' => __('models/company_branch_offices.plural')])]);

            return redirect(route('companies.branchOffices.index', ['companyBranchOffice' => $companyBranchOffice, 'company' => $company_id]));
        }

        return view('company_branch_offices.edit')->with(['companyBranchOffice' => $companyBranchOffice, 'company' => $companyBranchOffice->empresa]);
    }

    /**
     * Update the specified CompanyBranchOffice in storage.
     *
     * @param int $id
     * @param UpdateCompanyBranchOfficeRequest $request
     *
     * @return Response
     */
    public function update(UpdateCompanyBranchOfficeRequest $request, $company_id, $companyBranchOffice_id)
    {
        /** @var CompanyBranchOffice $companyBranchOffice */
        $companyBranchOffice = CompanyBranchOffice::find($companyBranchOffice_id);

        if (empty($companyBranchOffice)) {
            request()->session()->flash('error-message', [__('messages.not_found', ['model' => __('models/company_branch_offices.plural')])]);

            return redirect(route('companyBranchOffices.index'));
        }

        $companyBranchOffice->fill($request->validated());
        $companyBranchOffice->save();

        request()->session()->flash('success-message', [__('messages.updated', ['model' => __('models/company_branch_offices.plural')])]);

        return redirect(route('companies.show', ['company' => $company_id]));

        // return redirect(route('companies.branchOffices.index', ['company' => $companyBranchOffice->company]));
    }

    /**
     * Remove the specified CompanyBranchOffice from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($company_id, $companyBranchOffice_id)
    {
        /** @var CompanyBranchOffice $companyBranchOffice */
        $companyBranchOffice = CompanyBranchOffice::find($companyBranchOffice_id);


        if (empty($companyBranchOffice)) {
            request()->session()->flash('error-message', [__('messages.not_found', ['model' => __('models/company_branch_offices.plural')])]);

            return redirect(route('companies.show', ['company' => $company_id]));
        }

        $company_id = $companyBranchOffice->empresa_id;


        $companyBranchOffice->delete();

        request()->session()->flash('success-message', [__('messages.deleted', ['model' => __('models/company_branch_offices.plural')])]);

        return redirect(route('companies.show', ['company' => $company_id]));
    }
}
