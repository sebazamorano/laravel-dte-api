<!-- Identity Card Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('identity_card', __('models/companies.fields.identity_card').':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::text('identity_card', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline','id'=>'identity_card']) !!}
</div>

<!-- Line Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('original', 'Certificado Digital'.':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::file('original', null, ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline','id'=>'certificate']) !!}
</div>

<!-- Password Sii Field -->
<div class="my-2 px-2 w-full overflow-hidden sm:w-full md:w-full lg:w-1/2 xl:w-1/2">
    {!! Form::label('password', 'Password Certificado'.':', ['class' => 'text-grey-darker block uppercase tracking-wide text-xs font-bold mt-3 mb-1']) !!}
    {!! Form::password('password', ['class' => 'border form-input w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline']) !!}
</div>


<table class="items-center w-full bg-transparent border-collapse">
    <tr>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="factura_electronica" value="1" class="form-checkbox">
                <span class="ml-2"><label for="factura_electronica">Factura electrónica</label></span>
            </label>
        </th>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="nota_credito_electronica" value="7" class="form-checkbox">
                <span class="ml-2"><label for="nota_credito_electronica">Nota de crédito electrónica</label></span>
            </label>
        </th>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="nota_debito_electronica" value="6" class="form-checkbox">
                <span class="ml-2"><label for="nota_debito_electronica">Nota de débito electrónica</label></span>
            </label>
        </th>
    </tr>
    <tr>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="factura_exenta_electronica" value="2" class="form-checkbox">
                <span class="ml-2"><label for="factura_exenta_electronica">Factura exenta electrónica</label></span>
            </label>
        </th>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="boleta_electronica" value="20" class="form-checkbox">
                <span class="ml-2"><label for="boleta_electronica">Boleta electrónica</label></span>
            </label>
        </th>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="boleta_exenta_electronica" value="21" class="form-checkbox">
                <span class="ml-2"><label for="boleta_exenta_electronica">Boleta exenta electrónica</label></span>
            </label>
        </th>
    </tr>
    <tr>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
            <label class="inline-flex items-center">
                <input type="checkbox" name="documentoAutorizado[]" id="guia_despacho" value="5" class="form-checkbox">
                <span class="ml-2"><label for="guia_despacho">Guia de despacho</label></span>
            </label>
        </th>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
        </th>
        <th class="px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
        </th>
    </tr>
</table>

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
