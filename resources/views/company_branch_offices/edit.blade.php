@extends('layouts.admin')

@section('page-title') Editar @lang('models/company_branch_offices.singular') @endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -mt-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            @include('layouts.alerts')
                            {!! Form::model($companyBranchOffice, ['route' => ['companies.branchOffices.update', ['company' => $companyBranchOffice->empresa_id, 'companyBranchOffices' => $companyBranchOffice->id]], 'method' => 'patch', 'class'=> 'w-full flex flex-wrap overflow-hidden px-2']) !!}

                                @include('company_branch_offices.fields')

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
