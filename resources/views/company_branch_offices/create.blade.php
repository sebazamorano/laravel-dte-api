@extends('layouts.admin')

@section('page-title') Crear @lang('models/company_branch_offices.singular') @endsection

@section('content')
<div class="px-4 md:px-10 mx-auto w-full -mt-24">
    <div class="flex flex-wrap mt-4">
        <div class="w-full mb-12 xl:mb-0 px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        {!! Form::open(['route' => ['companies.branchOffices.store', ['company' => $company->id]], 'class'=> 'w-full flex flex-wrap overflow-hidden px-2']) !!}

                            @include('company_branch_offices.fields')

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

