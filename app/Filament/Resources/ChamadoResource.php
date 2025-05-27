<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChamadoResource\Pages;
use App\Models\Chamado;
use App\Models\Departamento;
use App\Models\ItemEstoque;
use App\Models\MovimentoEstoque;
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
            Forms\Components\Section::make('Informações do Solicitante')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('nome')
                                ->label('Nome Completo')
                                ->required()
                                ->maxLength(255),
                                
                            Forms\Components\TextInput::make('email')
                                ->label('E-mail Institucional')
                                ->email()
                                ->required()
                                ->maxLength(255),
                        ]),
                        
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('sala')
                                ->label('Sala/Local')
                                ->required()
                                ->maxLength(100),
                                
                            Forms\Components\TextInput::make('ramal')
                                ->label('Ramal')
                                ->required()
                                ->maxLength(20),
                                
                            Forms\Components\TextInput::make('patrimonio')
                                ->label('Nº Patrimônio')
                                ->default('0')
                                ->maxLength(50),
                        ]),
                ])
                ->collapsible(),
                
            Forms\Components\Section::make('Detalhes do Chamado')
                ->schema([
                    Forms\Components\Select::make('departamento_id')
                        ->label('Departamento Responsável')
                        ->options(Departamento::all()->pluck('nome', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                        
                    Forms\Components\Textarea::make('descricao')
                        ->label('Descrição do Problema')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull()
                        ->helperText('Descreva detalhadamente o problema encontrado'),
                ])
                ->collapsible(),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->poll('5s')
        ->columns([
            Tables\Columns\TextColumn::make('nome')
                ->label('Nome')
                ->searchable(),

            Tables\Columns\TextColumn::make('descricao')
                ->label('Descrição')
                ->limit(50)
                ->tooltip(fn (Chamado $record) => $record->descricao)
                ->searchable(),

            Tables\Columns\TextColumn::make('departamento.nome')
                ->label('Departamento')
                ->tooltip(fn ($record) => $record->departamento->nome ?? 'N/A'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Criado em')
                ->icon('heroicon-o-calendar')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->icon(fn (string $state) => match ($state) {
                    'aberto' => 'heroicon-o-clock',
                    'em_andamento' => 'heroicon-o-adjustments-horizontal',
                    'encerrado' => 'heroicon-o-check-circle',
                    default => null,
                })
                ->color(fn (string $state) => match ($state) {
                    'aberto' => 'gray',
                    'em_andamento' => 'warning',
                    'encerrado' => 'success',
                    default => 'secondary',
                })
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'aberto' => 'Aberto',
                    'em_andamento' => 'Em andamento',
                    'encerrado' => 'Encerrado',
                    default => ucfirst($state),
                }),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'aberto' => 'Aberto',
                    'em_andamento' => 'Em andamento',
                    'encerrado' => 'Encerrado',
                ]),

            Tables\Filters\Filter::make('created_at')
                ->label('Data de Abertura')
                ->form([
                    Forms\Components\DatePicker::make('from')->label('De'),
                    Forms\Components\DatePicker::make('until')->label('Até'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                }),
        ])
        ->defaultSort('created_at', 'desc')
        ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->tooltip('Visualizar chamado')
                    ->visible(fn (Chamado $record) => !$record->encerrado),

                Tables\Actions\Action::make('verDetalhes')
                    ->label('Ver Detalhes')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'encerrado')
                    ->modalHeading('Detalhes do Chamado Encerrado')
                    ->modalSubheading('Aqui você pode ver todos os detalhes do chamado após seu encerramento.')
                    ->form(fn (Chamado $record) => [
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome')
                            ->default($record->nome)
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->default($record->email)
                            ->disabled(),

                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição do Chamado')
                            ->default($record->descricao)
                            ->disabled()
                            ->rows(3),

                        Forms\Components\Textarea::make('solucao')
                            ->label('Relato / Solução')
                            ->default($record->solucao)
                            ->disabled()
                            ->rows(3),

                        Forms\Components\TextInput::make('encerrado_em')
                            ->label('Data de Encerramento')
                            ->default(fn () => $record->encerrado_em ? \Carbon\Carbon::parse($record->encerrado_em)->format('d/m/Y H:i') : null)
                            ->disabled(),

                        Forms\Components\Repeater::make('itens_usados')
                            ->label('Itens Utilizados')
                            ->schema([
                                Forms\Components\TextInput::make('item')
                                    ->label('Item')
                                    ->default(fn ($state) => $state['item'] ?? '')
                                    ->disabled(),

                                Forms\Components\TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->default(fn ($state) => $state['quantidade'] ?? '')
                                    ->disabled(),
                            ])
                            ->default(fn () => $record->itensEstoque?->map(function ($item) {
                                return [
                                    'item' => $item->nome,
                                    'quantidade' => $item->pivot->quantidade,
                                ];
                            })->toArray() ?? [])
                            ->disabled(),
                    ])
                    ->action(fn () => null),

                Tables\Actions\Action::make('atenderChamado')
                    ->label('Atender / Encerrar')
                    ->icon('heroicon-o-wrench')
                    ->color('primary')
                    ->visible(fn (Chamado $record) => !$record->encerrado)
                    ->modalHeading('Atendimento do Chamado')
                    ->form(fn (Chamado $record) => [
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nome')
                                ->label('Nome')
                                ->default($record->nome)
                                ->disabled(),

                            Forms\Components\TextInput::make('email')
                                ->label('E-mail')
                                ->default($record->email)
                                ->disabled(),

                            Forms\Components\TextInput::make('sala')
                                ->label('Sala / Local')
                                ->default($record->sala)
                                ->disabled(),

                            Forms\Components\TextInput::make('ramal')
                                ->label('Ramal')
                                ->default($record->ramal)
                                ->disabled(),
                        ]),

                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição do Chamado')
                            ->default($record->descricao)
                            ->disabled()
                            ->rows(3),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aberto' => 'Aberto',
                                'em_andamento' => 'Em andamento',
                                'encerrado' => 'Encerrado',
                            ])
                            ->reactive()
                            ->required()
                            ->default($record->status),

                        Forms\Components\Textarea::make('solucao')
                            ->label('Relato / Solução')
                            ->rows(3)
                            ->visible(fn (callable $get) => $get('status') === 'encerrado')
                            ->required(fn (callable $get) => $get('status') === 'encerrado'),

                        Forms\Components\Repeater::make('itens_usados')
                            ->label('Itens utilizados (opcional)')
                            ->schema([
                                Forms\Components\Select::make('item_estoque_id')
                                    ->label('Item de Estoque')
                                    ->options(ItemEstoque::pluck('nome', 'id'))
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                            ])
                            ->visible(fn (callable $get) => $get('status') === 'encerrado'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->status = $data['status'];
                        $record->solucao = $data['solucao'] ?? null;

                        if ($data['status'] === 'encerrado') {
                            $record->encerrado = true;
                            $record->encerrado_em = now();

                            if (!empty($data['itens_usados'])) {
                                foreach ($data['itens_usados'] as $itemUsado) {
                                    MovimentoEstoque::create([
                                        'item_estoque_id' => $itemUsado['item_estoque_id'],
                                        'chamado_id' => $record->id,
                                        'quantidade' => $itemUsado['quantidade'],
                                        'tipo' => 'saida',
                                    ]);

                                    $item = ItemEstoque::find($itemUsado['item_estoque_id']);
                                    if ($item) {
                                        $item->quantidade -= $itemUsado['quantidade'];
                                        $item->save();
                                    }
                                }
                            }
                        }

                        $record->save();
                    }),
            ]),
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
