<div class="block w-full overflow-x-auto">
    <table class="items-center w-full bg-transparent border-collapse">
        <thead>
        <tr>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Empresa ID</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.type')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.sii_code')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.name')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.address')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.address_xml')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.province')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/company_branch_offices.fields.municipality')</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider" colspan="3">@lang('crud.action')</th>
        </tr>
        </thead>
        <tbody>
        @if($companyBranchOffices !== null && count($companyBranchOffices) > 0)
            @foreach($companyBranchOffices as $companyBranchOffice)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->empresa_id }}</td>
                    {{-- <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"> --}}
                        @if($companyBranchOffice->tipo == 1)
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"> Matriz </td>
                        @else
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"> Sucursal </td>
                        @endif
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->sii_code }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->nombre }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->direccion }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->direccionXml }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->ciudad }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $companyBranchOffice->comuna }}</td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            {!! Form::open(['route' => ['companies.branchOffices.destroy', ['company' => $companyBranchOffice->empresa_id, 'companyBranchOffices' => $companyBranchOffice->id]], 'method' => 'delete']) !!}
                            <div class='flex'>
                                <a href="{{ route('companies.branchOffices.show', ['company' => $companyBranchOffice->empresa_id, 'companyBranchOffices' => $companyBranchOffice->id]) }}" class="text-blue-400 hover:text-blue-600 underline ml-2"><svg class="h-4 w-4 fill-current text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M.2 10a11 11 0 0 1 19.6 0A11 11 0 0 1 .2 10zm9.8 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg></a>
                                <a href="{{ route('companies.branchOffices.edit', ['company' => $companyBranchOffice->empresa_id, 'companyBranchOffices' => $companyBranchOffice->id]) }}" class="text-blue-400 hover:text-blue-600 underline ml-2"><svg class="h-4 w-4 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/></svg></a>
                                {!! Form::button('<svg class="h-4 w-4 fill-current text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/></svg>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs ml-2', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
