<div>
    <div class="block w-full overflow-x-auto">
        <table class="items-center w-full bg-transparent border-collapse">
            <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Tipo de Documento</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Folio desde</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Folio hasta</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Folio Actual</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Fecha Vencimiento</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">En Uso</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Completado</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50" colspan="3">@lang('crud.action')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cafs as $caf)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $caf->tipo_documento->nombre }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $caf->folioDesde }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $caf->folioHasta}}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $caf->folioUltimo }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $caf->fechaVencimiento)->format('d-m-Y') }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if($caf->enUso)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if($caf->completado)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if(!$caf->enUso)
                            {!! Form::open(['route' => ['cafs.setInUse', ['company' => $company->id, 'caf' => $caf->id]]]) !!}
                            {{ method_field('PATCH') }}
                            <div class='flex'>
                                {!! Form::button('<i class="fa fa-check" aria-hidden="true"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs ml-2', 'onclick' => 'return confirm("Se pondra el caf en uso")']) !!}  &nbsp; Poner en uso
                            </div>
                            {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $cafs->links() }}
    </div>

</div>
