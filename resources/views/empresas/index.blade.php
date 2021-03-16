@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Rut</td>
                                <td>Razón Social</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empresas as $empresa)
                                <tr>
                                    <td>{{ $empresa->rut }}</td>
                                    <td>{{ $empresa->razonSocial }}</td>
                                    <td> <a href="{{ route('empresas.show', ['empresa' => $empresa->id]) }}">Ver</a> </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
