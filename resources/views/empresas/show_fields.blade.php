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

<!-- Line Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('giro', __('models/companies.fields.line').':') !!}
    {!! Form::text('giro', $company->giro, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('fechaResolucion', 'Fecha resolución DTE:') !!}
    {!! Form::date('fechaResolucion', $company->fechaResolucion, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('numeroResolucion', 'Número resolución DTE:') !!}
    {!! Form::text('numeroResolucion', $company->numeroResolucion, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('fechaResolucionBoleta', 'Fecha resolución Boleta:') !!}
    {!! Form::date('fechaResolucionBoleta', $company->fechaResolucionBoleta, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('numeroResolucionBoleta', 'Número resolución Boleta:') !!}
    {!! Form::text('numeroResolucionBoleta', $company->numeroResolucionBoleta, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

