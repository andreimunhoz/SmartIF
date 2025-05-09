<?php

namespace App\Filament\Resources;

use App\Models\ItemEstoque;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ProdutoResource\Pages;

class ProdutoResource extends Resource
{
    protected static ?string $model = ItemEstoque::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Produtos';
    protected static ?string $pluralModelLabel = 'Produtos';
    protected static ?string $modelLabel = 'Produto';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                TextInput::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                DatePicker::make('data_validade')
                    ->label('Data de Validade')
                    ->nullable(),

                Textarea::make('descricao')
                    ->label('Descrição')
                    ->rows(3)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')->label('Nome')->searchable(),
                TextColumn::make('quantidade')->label('Quantidade'),
                TextColumn::make('data_validade')->label('Validade')->date(),
            ])
            ->defaultSort('nome');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}
