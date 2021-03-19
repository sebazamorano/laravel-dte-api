<div>
    <div class="block w-full overflow-x-auto">
        <table class="items-center w-full bg-transparent border-collapse">
            <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Rut</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Fecha de Vencimiento</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">En Uso</th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50" colspan="3">@lang('crud.action')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($company->certificados as $certificado)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ $certificado->rut }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ json_decode($certificado->subject)->CN }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $certificado->fechaVencimiento)->format('d-m-Y') }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if($certificado->enUso)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-times" aria-hidden="true"></i>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if(!$certificado->enUso)
                            {!! Form::open(['route' => ['certificadosDigitales.setInUse', ['company' => $company->id, 'certificadoDigital' => $certificado->id]]]) !!}
                                {{ method_field('PATCH') }}
                                <div class='flex'>
                                    {!! Form::button('<i class="fa fa-check" aria-hidden="true"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs ml-2', 'onclick' => 'return confirm("Se pondra el certificado en uso")']) !!}  &nbsp; Poner en uso
                                </div>
                            {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

</div>
