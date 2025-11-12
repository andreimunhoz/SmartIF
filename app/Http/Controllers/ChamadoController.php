<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller
{
    /**
     * Exibe uma lista de todos os chamados.
     */
    public function index()
    {
        $chamados = Chamado::with('requisitante')->latest()->paginate(10);
        return view('chamados.index', compact('chamados'));
    }

    /**
     * Mostra o formulário para criar um novo chamado.
     */
    public function create()
    {
        // return view('chamados.create');
        return "Formulário para criar novo chamado";
    }

    /**
     * Salva um novo chamado no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação dos dados (exemplo)
        $dadosValidados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao_problema' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta',
        ]);

        // Cria o chamado associado ao usuário logado
        $chamado = Auth::user()->chamadosRequisitados()->create($dadosValidados);

        // return redirect()->route('chamados.show', $chamado)->with('sucesso', 'Chamado aberto!');
        return "Chamado salvo com ID: " . $chamado->id;
    }

    /**
     * Exibe um chamado específico.
     */
    public function show(Chamado $chamado)
    {
        // Carrega os relacionamentos para evitar N+1 queries
        $chamado->load('requisitante', 'atendente', 'materiais');
        
        // return view('chamados.show', compact('chamado'));
        return "Exibindo detalhes do Chamado ID: " . $chamado->id;
    }

    /**
     * Mostra o formulário para editar um chamado (atendimento).
     */
    public function edit(Chamado $chamado)
    {
        // return view('chamados.edit', compact('chamado'));
        return "Formulário de edição/atendimento do Chamado ID: " . $chamado->id;
    }

    /**
     * Atualiza um chamado específico no banco de dados.
     * (É aqui que o atendente muda status, adiciona solução E baixa materiais)
     */
    public function update(Request $request, Chamado $chamado)
    {
        // Lógica de validação...
        
        // Ex: Mudar status
        $chamado->status = $request->input('status', $chamado->status);
        
        // Ex: Atribuir a si mesmo
        if ($request->has('atribuir_a_mim')) {
            $chamado->atendente_id = Auth::id();
        }

        // --- LÓGICA PRINCIPAL DO PROJETO ---
        // Aqui virá a lógica para dar baixa no estoque
        if ($request->has('materiais_utilizados')) {
            // Ex: $request->input('materiais_utilizados') = [ ['id' => 1, 'qtd' => 2], ['id' => 5, 'qtd' => 1] ]
            
            // 1. Loop nos materiais
            // 2. Anexa na tabela pivot (chamado_material) com a quantidade
            // 3. Subtrai a quantidade do Model 'Material' (controle de estoque)
            // Esta lógica precisa ser transacional (DB::transaction)
        }
        
        $chamado->save();

        // return redirect()->route('chamados.show', $chamado)->with('sucesso', 'Chamado atualizado!');
        return "Chamado ID: " . $chamado->id . " atualizado.";
    }

    /**
     * Remove (ou cancela) um chamado.
     */
    public function destroy(Chamado $chamado)
    {
        // Implementar regra de negócio (só pode deletar se for 'aberto'?)
        // $chamado->delete();
        
        // Ou podemos "cancelar"
        $chamado->status = 'cancelado';
        $chamado->save();

        // return redirect()->route('chamados.index')->with('sucesso', 'Chamado cancelado.');
        return "Chamado ID: " . $chamado->id . " cancelado.";
    }
}