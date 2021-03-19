@extends('layouts.admin')

@section('page-title')
    <div class="flex flex-wrap -mx-2 overflow-hidden px-8 lg:px-0 sm:px-0 xl:px-0">
        <div class="my-2 px-2 overflow-hidden flex">Empresas</div>
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
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                                <a class="justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out" style="margin-top: -10px;margin-bottom: 5px" href="{{ route('companies.create') }}">@lang('crud.add_new')</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="block w-full overflow-x-auto">
                            <table class="items-center w-full bg-transparent border-collapse">
                                <thead>
                                <tr>
                                    @if(App::environment(['local', 'staging', 'dev']))<th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Certificación</th>@endif
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/companies.fields.name')</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/companies.fields.identity_card')</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/companies.fields.line')</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50" colspan="3">@lang('crud.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($companies as $company)
                                    <tr>
                                        @if(App::environment(['local', 'staging', 'dev']))
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <a href="{{ route('certificacion.index', [$company->id]) }}" class="text-black hover:text-black underline ml-2"><svg class="h-4 w-4 fill-current text-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg></a>
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $company->razonSocial }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $company->rut }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $company->giro }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                            {!! Form::open(['route' => ['companies.destroy', $company->id], 'method' => 'delete']) !!}
                                            <div class='flex'>
                                                <a href="{{ route('companies.show', [$company->id]) }}" class="text-blue-400 hover:text-blue-600 underline ml-2"><svg class="h-4 w-4 fill-current text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></a>
                                                <a href="{{ route('companies.edit', [$company->id]) }}" class="text-blue-400 hover:text-blue-600 underline ml-2"><svg class="h-4 w-4 fill-current text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg></a>
                                                {!! Form::button('<svg class="h-4 w-4 fill-current text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs ml-2', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                                            </div>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            {{ $companies->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
