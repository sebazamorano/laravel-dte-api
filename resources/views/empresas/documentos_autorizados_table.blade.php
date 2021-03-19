<div>
    <div class="block w-full overflow-x-auto">
        <table class="items-center w-full bg-transparent border-collapse">
            <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/sii_economic_activities.fields.code')</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">@lang('models/sii_economic_activities.fields.description')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($company->documentosAutorizados as $documentoAutorizado)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $documentoAutorizado->nombre }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $documentoAutorizado->tipoDTE }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

</div>
