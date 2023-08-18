<!-- Identity Card Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('rut', __('models/companies.fields.identity_card').':') !!}
    {!! Form::text('rut', $company->rut, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

<!-- Name Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('razonSocial', __('models/companies.fields.name').':') !!}
    {!! Form::text('razonSocial', $company->razonSocial, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<!-- Line Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('giro', __('models/companies.fields.line').':') !!}
    {!! Form::text('giro', $company->giro, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('fechaResolucion', 'Fecha resolución DTE:') !!}
    {!! Form::date('fechaResolucion', $company->fechaResolucion, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('numeroResolucion', 'Número resolución DTE:') !!}
    {!! Form::text('numeroResolucion', $company->numeroResolucion, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('fechaResolucionBoleta', 'Fecha resolución Boleta:') !!}
    {!! Form::date('fechaResolucionBoleta', $company->fechaResolucionBoleta, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('numeroResolucionBoleta', 'Número resolución Boleta:') !!}
    {!! Form::text('numeroResolucionBoleta', $company->numeroResolucionBoleta, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('contactoSii', 'Email contacto sii:') !!}
    {!! Form::text('contactoSii', $company->contactoSii, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('contactoEmpresas', 'Email contacto empresas:') !!}
    {!! Form::text('contactoEmpresas', $company->contactoEmpresas, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('passwordSii', 'Contraseña SII:') !!}
    {!! Form::input('password', 'passwordSii', $company->passwordSii, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500']) !!}
</div>

<!-- Submit Field -->
<div class="w-full px-2 mt-5 align-right flex">
    <div class="md:w-8/12 sm:w-full lg:w-9/12 xl:w-9/12">
        &nbsp;
    </div>
    <div class="md:w-4/12 sm:w-full lg:w-3/12 xl:w-3/12 sm:px-auto md:px-2 my-2 grid gap-2 grid-cols-2">
        {!! Form::submit(__('crud.save'), ['class' => 'flex justify-center w-full px-3 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out']) !!}

        <a href="{{ route('companies.index') }}" class="flex justify-center content-center w-full px-3 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out">@lang('crud.cancel')</a>
    </div>
</div>
