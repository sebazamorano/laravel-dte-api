@extends('layouts.admin')

@section('page-title')
<div class="flex flex-wrap -mx-2 overflow-hidden px-8 lg:px-0 sm:px-0 xl:px-0">
    <div class="my-2 px-2 overflow-hidden flex">@lang('models/company_branch_offices.plural')</div>
    <div class="my-2 px-2 flex ml-auto"></div>
</div>
@endsection

@section('content')
<div class="px-4 md:px-10 mx-auto w-full -mt-24">
    <div class="flex flex-wrap mt-4">
        <div class="w-full mb-12 xl:mb-0 px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold text-base text-gray-800">
                                Listado
                            </h3>
                        </div>
                        <div class="my-2 px-2 flex ml-auto"><a class="justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('companies.branchOffices.create', ['company' => $company->id]) }}">@lang('crud.add_new')</a></div>
                    </div>
                </div>
                @include('company_branch_offices.table')
            </div>
        </div>
    </div>
</div>
@endsection

