<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class DocumentoAutorizadoController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $company)
    {
        return view('empresas.documentos_autorizados_edit')->with(compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Empresa $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $company)
    {
        $documentosAutorizados = $request->input('documentoAutorizado');
        $company->documentosAutorizados()->sync($documentosAutorizados);

        request()->session()->flash('success-message', [__('messages.updated_multiple', ['model' => 'Documentos Autorizados'])]);

        return redirect(route('companies.show', ['company' => $company->id]));
    }
}
