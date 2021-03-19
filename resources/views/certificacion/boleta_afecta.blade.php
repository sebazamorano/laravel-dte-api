@extends('layouts.admin')

@section('page-title')
    @lang('models/companies.singular') {{ $company->razonSocial }}
@endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -m-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4 z-10">

                <div class="w-full mx-auto items-center flex justify-between md:flex-no-wrap flex-wrap md:px-10 px-4">
                    <h1 class="text-white text-sm uppercase hidden lg:inline-block font-semibold">
                        SET BOLETA AFECTA
                    </h1>
                </div>

                {!! Form::open(['route' => ['certificacion.boleta_afecta_procesar', $company->id], 'method' => 'POST']) !!}
                    <div class="bg-white rounded px-3 py-3 font-bold">
                        <!-- Identity Card Field -->
                        <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                            {!! Form::label('rut', __('models/companies.fields.identity_card').':') !!}
                            {!! Form::text('rut', $company->rut, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
                        </div>

                        <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                            {!! Form::label('date', 'Fecha :') !!}
                            {!! Form::date('date', null, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'required']) !!}
                        </div>
                    </div>

                    <!-- Provider Field -->
                    <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
                        {!! Form::submit('Generar', ['class' => 'mt-3 bg-green-500 hover:text-green-700 text-white font-bold py-2 px-4 rounded float-right ml-3 pointer cursor-pointer']) !!}

                        <a href="{{ route('certificacion.index', $company->id) }}" class="mt-3 bg-red-500 hover:text-red-700 text-white font-bold py-2 px-4 rounded float-right ml-3">
                            @lang('crud.back')
                        </a>
                    </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
