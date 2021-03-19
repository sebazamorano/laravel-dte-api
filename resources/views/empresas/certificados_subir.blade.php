@extends('layouts.admin')

@section('page-title') Sincronizar con SII @endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -mt-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            {!! Form::open(['route' => ['certificadosDigitales.upload', $company->id], 'class'=> 'flex flex-wrap -mx-2 overflow-hidden px-8 lg:px-0 sm:px-0 xl:px-0 w-full', 'files' => true]) !!}

                                <!-- Line Field -->
                                <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
                                    {!! Form::label('original', 'Certificado Digital'.':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
                                    {!! Form::file('original', null, ['class' => 'form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline','id'=>'certificate']) !!}
                                </div>

                                <!-- Password Sii Field -->
                                <div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
                                    {!! Form::label('password', 'Password Certificado'.':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
                                    {!! Form::text('password', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
                                </div>

                                <!-- Submit Field -->
                                <div class="my-2 px-2 flex w-full">

                                    <div class="w-3/4 overflow-hidden">

                                        &nbsp;

                                    </div>

                                    <div class="w-1/4 my-2 px-2 grid gap-4 grid-cols-2">
                                        {!! Form::submit(__('crud.save'), ['class' => 'flex justify-center w-full px-3 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out']) !!}
                                        <a href="{{ route('companies.show', ['company' => $company->id]) }}" class="flex justify-center content-center w-full px-3 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out">@lang('crud.cancel')</a>
                                    </div>
                                </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

