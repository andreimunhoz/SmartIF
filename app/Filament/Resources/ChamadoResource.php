<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChamadoResource\Pages;
use App\Models\Chamado;
use App\Models\Departamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class ChamadoResource extends Resource
{
    protected static ?string $model = Chamado::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Chamados';
    protected static ?string $pluralModelLabel = 'Chamados';
    protected static ?string $modelLabel = 'Chamado';
    protected static ?string $navigationGroup = 'Gestão';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('descricao')
                    ->label('Descrição')
                    ->required()
                    ->rows(4),

                Forms\Components\TextInput::make('patrimonio')
                    ->label('Nº Patrimonial')
                    ->default('0')
                    ->maxLength(50),

                Forms\Components\TextInput::make('sala')
                    ->label('Sala / Local')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('ramal')
                    ->label('Ramal')
                    ->required()
                    ->maxLength(20),

                Forms\Components\Select::make('departamento_id')
                    ->label('Departamento Responsável')
                    ->relationship('departamento', 'nome')
                    ->required()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                Tables\Columns\TextColumn::make('patrimonio')->label('Patrimônio'),
                Tables\Columns\TextColumn::make('sala')->label('Sala'),
                Tables\Columns\TextColumn::make('ramal')->label('Ramal'),
                Tables\Columns\TextColumn::make('departamento.nome')->label('Departamento'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data de Abertura')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver'),
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Excluir Selecionados'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChamados::route('/'),
            'create' => Pages\CreateChamado::route('/criar'),
            'edit' => Pages\EditChamado::route('/{record}/editar'),
        ];
    }
}
