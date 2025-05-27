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
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
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
            // Seção principal com campos básicos
            Forms\Components\Section::make('Dados do Produto')
                ->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            TextInput::make('nome')
                                ->label('Nome do Produto')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(['md' => 2]),
                                
                            TextInput::make('quantidade')
                                ->label('Quantidade em Estoque')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->default(0),
                                
                            DatePicker::make('data_validade')
                                ->label('Data de Validade')
                                ->nullable()
                                ->displayFormat('d/m/Y'),
                        ])
                        ->columns(['md' => 2, 'lg' => 3]),
                ])
                ->collapsible(),
                
            // Seção dedicada para a imagem
            Forms\Components\Section::make('Imagem do Produto')
                ->schema([
                    Forms\Components\FileUpload::make('imagem')
                        ->label('')
                        ->image()
                        ->directory('produtos')
                        ->disk('public')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetWidth('300')
                        ->imageResizeTargetHeight('300')
                        ->deleteUploadedFileUsing(function ($file) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                        })
                        ->panelLayout('integrated')
                        ->removeUploadedFileButtonPosition('right')
                        ->preserveFilenames()
                        ->openable()
                        ->downloadable()
                        ->previewable(true)
                        ->columnSpanFull()
                        ->helperText('Envie uma imagem quadrada (300x300 pixels) para melhor visualização'),
                ])
                ->collapsible(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagem')
                    ->label('Imagem')
                    ->circular(),
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