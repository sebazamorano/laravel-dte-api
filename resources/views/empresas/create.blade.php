@extends('layouts.admin')

@section('page-title') Crear @lang('models/companies.singular') @endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -mt-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            {!! Form::open(['route' => 'companies.store', 'class'=> 'flex flex-wrap -mx-2 overflow-hidden px-8 lg:px-0 sm:px-0 xl:px-0 w-full', 'files' => true]) !!}

                            @include('empresas.sync_sii_fields')

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

