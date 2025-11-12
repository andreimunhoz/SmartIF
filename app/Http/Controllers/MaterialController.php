<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Exibe a lista de itens no estoque.
     */
    public function index()
    {
        $materiais = Material::latest()->paginate(10);
        // return view('materiais.index', compact('materiais'));
        return "Lista de todos os materiais (estoque)";
    }

    /**
     * Mostra o formulário para adicionar um novo material.
     */
    public function create()
    {
        // return view('materiais.create');
        return "Formulário para novo item de estoque";
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

        // return redirect()->route('materiais.index')->with('sucesso', 'Material cadastrado!');
        return "Material salvo com ID: " . $material->id;
    }

    /**
     * Exibe os detalhes de um material.
     */
    public function show(Material $material)
    {
        // return view('materiais.show', compact('material'));
        return "Detalhes do Material ID: " . $material->id;
    }

    /**
     * Mostra o formulário de edição de um material.
     */
    public function edit(Material $material)
    {
        // return view('materiais.edit', compact('material'));
        return "Formulário de edição do Material ID: " . $material->id;
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

        // return redirect()->route('materiais.index')->with('sucesso', 'Material atualizado!');
        return "Material ID: " . $material->id . " atualizado.";
    }

    /**
     * Remove (desativa) um material do estoque.
     */
    public function destroy(Material $material)
    {
        // Estamos usando SoftDeletes, então o registro não será apagado
        // permanentemente, apenas marcado como "deleted_at".
        // Isso preserva o histórico.
        $material->delete();

        // return redirect()->route('materiais.index')->with('sucesso', 'Material desativado.');
        return "Material ID: " . $material->id . " desativado (Soft Delete).";
    }
}