@extends('layouts.admin')

@section('page-title')
    Documentos Autorizados - {{ $company->razonSocial }}
@endsection

@section('content')
    <div class="px-4 md:px-10 mx-auto w-full -m-24">
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4 z-10">
                <div class="bg-white rounded px-3 py-3 font-bold">

                    {!! Form::open(['route' => ['documentosAutorizados.update', ['company' => $company->id]], 'class'=> 'w-full flex flex-wrap overflow-hidden px-2']) !!}
                        {{ method_field('PATCH') }}

                        <table class="items-center w-full bg-transparent border-collapse">
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="1" class="form-checkbox" @if($company->documentoEstaAutorizado(33)) checked @endif>
                                        <span class="ml-2">Factura electrónica</span>
                                    </label>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="7" class="form-checkbox" @if($company->documentoEstaAutorizado(61)) checked @endif>
                                        <span class="ml-2">Nota de crédito electrónica</span>
                                    </label>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="6" class="form-checkbox" @if($company->documentoEstaAutorizado(56)) checked @endif>
                                        <span class="ml-2">Nota de débito electrónica</span>
                                    </label>
                                </th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="2" class="form-checkbox" @if($company->documentoEstaAutorizado(34)) checked @endif>
                                        <span class="ml-2">Factura exenta electrónica</span>
                                    </label>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="20" class="form-checkbox" @if($company->documentoEstaAutorizado(39)) checked @endif>
                                        <span class="ml-2">Boleta electrónica</span>
                                    </label>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="21" class="form-checkbox" @if($company->documentoEstaAutorizado(41)) checked @endif>
                                        <span class="ml-2">Boleta exenta electrónica</span>
                                    </label>
                                </th>

                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                </th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="documentoAutorizado[]" value="5" class="form-checkbox" @if($company->documentoEstaAutorizado(52)) checked @endif>
                                        <span class="ml-2">Guia de despacho</span>
                                    </label>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                </th>
                            </tr>
                        </table>

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
@endsection
