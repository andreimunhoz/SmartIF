<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChamadoPublicoController extends Controller
{
    public function create()
    {
        $departamentos = Departamento::all();
        return view('chamado.publico', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'descricao' => 'required|string',
            'patrimonio' => 'nullable|string|max:50',
            'sala' => 'required|string|max:100',
            'ramal' => 'required|string|max:20',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        if (empty($validated['patrimonio'])) {
            $validated['patrimonio'] = '0';
        }

        $chamado = Chamado::create($validated);

        // Envia e-mail para o departamento
        $departamento = Departamento::find($validated['departamento_id']);

        Mail::raw("Novo chamado recebido de {$chamado->nome}.\n\nDescrição: {$chamado->descricao}\nSala: {$chamado->sala}\nRamal: {$chamado->ramal}\nPatrimônio: {$chamado->patrimonio}", function ($message) use ($departamento) {
            $message->to($departamento->email)
                    ->subject('Novo chamado recebido');
        });

        return redirect()->route('chamado.create')->with('success', 'Chamado enviado com sucesso!');
    }
}
