<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MaterialController extends Controller
{
    /**
     * Exibe a lista de itens no estoque.
     */
    public function index()
    {
        $materiais = Material::latest()->paginate(10);
        // Ajustado para retornar a view
        return view('materiais.index', compact('materiais'));
    }

    /**
     * Mostra o formulário para adicionar um novo material.
     */
    public function create()
    {
        // Ajustado para retornar a view
        return view('materiais.create');
    }

    /**
     * Salva um novo material no banco de dados.
     */
    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'codigo_patrimonio' => 'nullable|string|max:100|unique:materiais',
            'quantidade_em_estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'data_de_validade' => 'nullable|date',
            'valor_de_custo' => 'required|numeric|min:0',
        ]);

        $material = Material::create($dadosValidados);

        // Ajustado para redirecionar com mensagem
        return redirect()->route('materiais.index')
                         ->with('sucesso', 'Material cadastrado com sucesso!');
    }

    /**
     * Exibe os detalhes de um material.
     * (Opcional, mas bom ter)
     */
    public function show(Material $material)
    {
        // Ajustado para retornar a view
        return view('materiais.show', compact('material'));
    }

    /**
     * Mostra o formulário de edição de um material.
     */
    public function edit(Material $material)
    {
        // Ajustado para retornar a view
        return view('materiais.edit', compact('material'));
    }

    /**
     * Atualiza um material no banco de dados.
     */
    public function update(Request $request, Material $material)
    {
        $dadosValidados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'codigo_patrimonio' => 'nullable|string|max:100|unique:materiais,codigo_patrimonio,' . $material->id,
            'quantidade_em_estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'data_de_validade' => 'nullable|date',
            'valor_de_custo' => 'required|numeric|min:0',
        ]);

        $material->update($dadosValidados);

        // Ajustado para redirecionar com mensagem
        return redirect()->route('materiais.index')
                         ->with('sucesso', 'Material atualizado com sucesso!');
    }

    /**
     * Remove (desativa) um material do estoque.
     */
    public function destroy(Material $material)
    {
        $material->delete(); // SoftDeletes

        // Ajustado para redirecionar com mensagem
        return redirect()->route('materiais.index')
                         ->with('sucesso', 'Material desativado com sucesso.');
    }
}