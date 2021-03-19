@extends('layouts.admin')

@section('page-title')
    @lang('models/companies.singular') {{ $company->razonSocial }}
@endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -m-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4 z-10">
                <div class="bg-white rounded px-3 py-3 font-bold">

                    <!-- Identity Card Field -->
                    <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                        {!! Form::label('rut', __('models/companies.fields.identity_card').':') !!}
                        {!! Form::text('rut', $company->rut, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
                    </div>

                    <!-- Name Field -->
                    <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                        {!! Form::label('razonSocial', __('models/companies.fields.name').':') !!}
                        {!! Form::text('razonSocial', $company->razonSocial, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
                    </div>

                    <!-- Name Field -->
                    <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                        <a href="{{ route('companies.index') }}" class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded ml-3">
                            Set pruebas/simulación
                        </a>

                        <a href="{{ route('certificacion.boleta_afecta_form', $company->id) }}" class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded ml-3">
                            Set boleta afecta
                        </a>

                        <a href="{{ route('certificacion.boleta_exenta_form', $company->id) }}" class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded ml-3">
                            Set boleta exenta
                        </a>

                        <a href="{{ route('companies.index') }}" class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded ml-3">
                            Etapa de intercambio
                        </a>


                    </div>


                    <!-- Provider Field -->
                    <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                        <a href="{{ route('companies.index') }}" class="mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded float-right ml-3">
                            @lang('crud.back')
                        </a>
                        <a class="mt-3 bg-yellow-500 hover:text-yellow-700 text-white font-bold py-2 px-4 rounded float-right" href="{{ route('companies.edit', ['company' => $company->id]) }}">
                            Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
