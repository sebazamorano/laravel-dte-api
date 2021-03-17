
<!-- Type Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('tipo', __('models/company_branch_offices.fields.type').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    <label class="checkbox-inline">
        {!! Form::hidden('tipo', 0) !!}
        Matriz
        {!! Form::radio('tipo', '1', null, ['class' => 'my-3 mr-2 leading-tight']) !!}
        Sucursal
    {!! Form::radio('tipo', '2', null, ['class' => 'my-3 mr-2 leading-tight']) !!}
</div>

<!-- Nombre Code Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('nombre', __('models/company_branch_offices.fields.name').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::text('nombre', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
</div>

<!-- Sii Code Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('codigo', __('models/company_branch_offices.fields.sii_code').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::number('codigo', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
</div>

<!-- Address Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('direccion', __('models/company_branch_offices.fields.address').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::text('direccion', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline','id'=>'address']) !!}
</div>

<!-- Address Xml Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('direccionXml', __('models/company_branch_offices.fields.address_xml').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::text('direccionXml', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline','id'=>'address_xml']) !!}
</div>

<!-- City Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('ciudad', __('models/company_branch_offices.fields.province').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::text('ciudad', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
</div>

<!-- Municipality Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('comuna', __('models/company_branch_offices.fields.municipality').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::text('comuna', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
</div>

<!-- Submit Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
</div>

<div class="my-2 px-2 flex w-full">

    <div class="w-3/4 overflow-hidden">

        &nbsp;

    </div>

    <div class="w-1/4 my-2 px-2 grid gap-4 grid-cols-2">
        {!! Form::submit(__('crud.save'), ['class' => 'flex justify-center w-full px-3 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out']) !!}
        <a href="{{ route('companies.show', ['company' => $company->id]) }}" class="flex justify-center content-center w-full px-3 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out">@lang('crud.cancel')</a>
    </div>
</div>

