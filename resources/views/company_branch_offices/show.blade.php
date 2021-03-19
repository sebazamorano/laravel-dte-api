@extends('layouts.admin')

@section('page-title')
    @lang('models/company_branch_offices.singular')
@endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -m-24">
            <div class="flex flex-wrap mt-4">
                <div class="w-full mb-12 xl:mb-0 px-4 z-10">
                    <div class="bg-white rounded px-3 py-3 font-bold">
                        @include('company_branch_offices.show_fields')
                        <a href="{{ route('companies.show', ['company' =>  $companyBranchOffice->empresa_id]) }}" style="position: absolute; right:7%;  " class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded">
                            @lang('crud.back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
@endsection
