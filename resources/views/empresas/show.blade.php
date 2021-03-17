@extends('layouts.admin')

@section('page-title')
    @lang('models/companies.singular')
@endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -m-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4 z-10">
                <div class="bg-white rounded px-3 py-3 font-bold">
                @include('empresas.show_fields')
                <!-- Provider Field -->
                    <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                        <a href="{{ route('companies.index') }}"  class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded float-right">
                            @lang('crud.back')
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-base text-gray-800">
                                    Listado Sucursales
                                </h3>
                            </div>
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                                <a class="justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600
                                    border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700
                                    focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('companies.branchOffices.create', ['company' => $company->id]) }}">
                                    @lang('crud.add_new')
                                </a>
                            </div>
                        </div>
                    </div>
                    @include('company_branch_offices.table')
                </div>
            </div>
        </div>

        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-base text-gray-800">
                                    Listado Actividades Economicas
                                </h3>
                            </div>
                        </div>
                    </div>
                    @include('empresas.actividades_economicas_table')
                </div>
            </div>
        </div>

        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-base text-gray-800">
                                    Listado Documentos autorizados
                                </h3>
                            </div>
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                                <a class="justify-center w-full px-4 py-2 text-sm font-medium text-white bg-yellow-600
                                    border border-transparent rounded-md hover:bg-yellow-500 focus:outline-none focus:border-yellow-700
                                    focus:shadow-outline-yellow active:bg-yellow-700 transition duration-150 ease-in-out" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('documentosAutorizados.edit', ['company' => $company->id]) }}">
                                    @lang('crud.edit')
                                </a>
                            </div>
                        </div>
                    </div>
                    @include('empresas.documentos_autorizados_table')
                </div>
            </div>
        </div>
    </div>

@endsection
