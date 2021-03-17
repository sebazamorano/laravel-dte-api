<!-- Company Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('company_id', __('models/company_branch_offices.fields.company_id').':') !!}
    {!! Form::text('name', $companyBranchOffice->company_id, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>

<!-- Sii Code Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('sii_code', __('models/company_branch_offices.fields.sii_code').':') !!}
    {!! Form::text('name', $companyBranchOffice->sii_code, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Name Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('name', __('models/company_branch_offices.fields.name').':') !!}
    {!! Form::text('name', $companyBranchOffice->name, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Phone Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('phone', __('models/company_branch_offices.fields.phone').':') !!}
    {!! Form::text('name', $companyBranchOffice->phone, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Address Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('address', __('models/company_branch_offices.fields.address').':') !!}
    {!! Form::text('name', $companyBranchOffice->address, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Address Xml Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('address_xml', __('models/company_branch_offices.fields.address_xml').':') !!}
    {!! Form::text('name', $companyBranchOffice->address_xml, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Region Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('region', __('models/company_branch_offices.fields.region').':') !!}
    {!! Form::text('name', $companyBranchOffice->region, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- City Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('city', __('models/company_branch_offices.fields.city').':') !!}
    {!! Form::text('name', $companyBranchOffice->city, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Municipality Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('municipality', __('models/company_branch_offices.fields.municipality').':') !!}
    {!! Form::text('name', $companyBranchOffice->municipality, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Api Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('api_id', __('models/company_branch_offices.fields.api_id').':') !!}
    {!! Form::text('name', $companyBranchOffice->api_id, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Region Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('region_id', __('models/company_branch_offices.fields.region_id').':') !!}
    {!! Form::text('name', $companyBranchOffice->region_id, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- City Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('province_id', __('models/company_branch_offices.fields.province_id').':') !!}
    {!! Form::text('name', $companyBranchOffice->province_id, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Municipality Id Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('municipality_id', __('models/company_branch_offices.fields.municipality_id').':') !!}
    {!! Form::text('name', $companyBranchOffice->municipality_id, ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
</div>


<!-- Type Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-full xl:w-full">
    {!! Form::label('type', __('models/company_branch_offices.fields.type').':') !!}


    @if($companyBranchOffice->type == 1)
        {!! Form::text('name', "Matriz", ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
    @else
        {!! Form::text('name', "Sucursal", ['class' => 'appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'disabled' => 'disabled']) !!}
    @endif

</div>
