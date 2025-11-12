<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Routing\Controller;

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
        return view('chamados.create');
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

        // Redireciona para a rota 'chamados.show' (página de detalhes)
        // e envia uma mensagem de sucesso para a sessão.
        return redirect()->route('chamados.show', $chamado)
                        ->with('sucesso', 'Chamado aberto com sucesso!');
}

    /**
     * Exibe um chamado específico.
     */
    public function show(Chamado $chamado)
    {
        // Carrega os relacionamentos para evitar N+1 queries
        $chamado->load('requisitante', 'atendente', 'materiais');
        
        return view('chamados.show', compact('chamado'));
        
    }

    /**
     * Mostra o formulário para editar um chamado (atendimento).
     */
    public function edit(Chamado $chamado)
{
    // Pega todos os materiais que tenham estoque
    $materiais = Material::where('quantidade_em_estoque', '>', 0)
                        ->orderBy('nome')
                        ->get();

    // Retorna a view de edição, passando o chamado e a lista de materiais
    return view('chamados.edit', compact('chamado', 'materiais'));
}

    /**
     * Atualiza um chamado específico no banco de dados.
     * (É aqui que o atendente muda status, adiciona solução E baixa materiais)
     */
    public function update(Request $request, Chamado $chamado)
{
    // Validação básica (pode ser mais complexa)
    $dadosValidados = $request->validate([
        'status' => 'required|in:aberto,em_andamento,concluido,cancelado',
        'solucao_aplicada' => 'nullable|string',
        'atribuir_a_mim' => 'nullable|boolean',
        'materiais_utilizados' => 'nullable|array',
        'materiais_utilizados.*' => 'nullable|integer|min:0', // Valida cada item do array
    ]);

    // Validação condicional: Solução é obrigatória se estiver concluindo
    if ($dadosValidados['status'] == 'concluido' && empty($dadosValidados['solucao_aplicada'])) {
        return back()->withErrors(['solucao_aplicada' => 'A solução é obrigatória para concluir o chamado.'])->withInput();
    }

    try {
        // Usa uma transação para garantir a integridade dos dados
        // Se algo falhar (ex: falta de estoque), tudo é revertido.
        DB::transaction(function () use ($request, $chamado, $dadosValidados) {

            // 1. Atualiza os campos simples do chamado
            $chamado->status = $dadosValidados['status'];
            $chamado->solucao_aplicada = $dadosValidados['solucao_aplicada'];

            // 2. Atribui o chamado ao atendente logado
            if ($request->has('atribuir_a_mim') && !$chamado->atendente_id) {
                $chamado->atendente_id = Auth::id();
            }

            // 3. Define a data de conclusão
            if ($chamado->status == 'concluido' && !$chamado->concluido_em) {
                $chamado->concluido_em = now();
            }

            // 4. LÓGICA DE BAIXA DE ESTOQUE
            if ($request->has('materiais_utilizados')) {
                $materiaisParaSincronizar = [];

                foreach ($request->input('materiais_utilizados') as $material_id => $quantidade) {
                    // Só processa se a quantidade for maior que zero
                    if (empty($quantidade) || $quantidade <= 0) {
                        continue;
                    }

                    $material = Material::findOrFail($material_id); // Pega o item do estoque

                    // Verifica se há estoque suficiente
                    if ($material->quantidade_em_estoque < $quantidade) {
                        // Se não tiver, falha a transação
                        throw new Exception("Estoque insuficiente para o item: {$material->nome}. Disponível: {$material->quantidade_em_estoque}, Solicitado: {$quantidade}");
                    }

                    // 1. Subtrai do estoque
                    // Usar decrement é mais seguro contra "race conditions"
                    $material->decrement('quantidade_em_estoque', $quantidade);

                    // 2. Adiciona ao array para salvar na tabela pivot
                    // (Isso registra o histórico de uso)
                    $materiaisParaSincronizar[$material_id] = ['quantidade_utilizada' => $quantidade];
                }

                // Sincroniza a tabela pivot (chamado_material)
                // 'syncWithoutDetaching' adiciona os novos itens sem remover os antigos
                $chamado->materiais()->syncWithoutDetaching($materiaisParaSincronizar);
            }

            // Salva o chamado (com status, atendente, solução, etc.)
            $chamado->save();
        });

    } catch (Exception $e) {
        // Se a transação falhar (ex: falta de estoque), volta com o erro
        return back()->withErrors(['estoque' => $e->getMessage()])->withInput();
    }

    // Se tudo deu certo, redireciona para a página de detalhes
    return redirect()->route('chamados.show', $chamado)
                     ->with('sucesso', 'Atendimento salvo e estoque atualizado!');
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