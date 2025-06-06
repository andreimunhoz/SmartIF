<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChamadoResource\Pages;
use App\Models\Chamado;
use App\Models\Departamento;
use App\Models\ItemEstoque;
use App\Models\MovimentoEstoque;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder; // Importação para filtros
use Filament\Tables\Filters\SelectFilter; // Importação para filtros
use Filament\Tables\Filters\Filter;      // Importação para filtros
use Filament\Forms\Components\DatePicker; // Importação para filtros
use Filament\Tables\Grouping\Group;       // Importação para Agrupamento

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
        // Seu formulário de criação/edição permanece o mesmo
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
            ->recordUrl(null)
            // MELHORIA: Ação de clique na linha inteira
            ->recordAction(fn (Chamado $record) => $record->status === 'encerrado' ? 'visualizar' : 'atender')
            // MELHORIA: Ordenação padrão pelos mais recentes
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),

                // MELHORIA: Coluna de solicitante mais informativa
                Tables\Columns\TextColumn::make('nome')
                    ->label('Solicitante')
                    ->description(fn (Chamado $record): string => $record->email)
                    ->searchable(['nome', 'email']), // Busca por nome e email

                Tables\Columns\TextColumn::make('descricao')->label('Descrição')->limit(40)->tooltip(fn (Chamado $record) => $record->descricao)->searchable(),
                Tables\Columns\TextColumn::make('departamento.nome')->label('Departamento')->tooltip(fn ($record) => $record->departamento?->nome ?? 'N/A'),

                // MELHORIA: Data relativa (ex: "há 2 horas")
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->since()
                    ->tooltip(fn (Chamado $record): string => $record->created_at->format('d/m/Y H:i'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state) => match ($state) {
                        'aberto' => 'heroicon-o-clock',
                        'em_andamento' => 'heroicon-o-adjustments-horizontal',
                        'encerrado' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'aberto' => 'gray',
                        'em_andamento' => 'warning',
                        'encerrado' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
            ])
            // MELHORIA: Filtros avançados
            ->filters([
                SelectFilter::make('departamento')
                    ->relationship('departamento', 'nome')
                    ->searchable()
                    ->preload()
                    ->label('Departamento'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Criado de'),
                        DatePicker::make('created_until')->label('Criado até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                // Sua lógica de ações condicionais está mantida
                Tables\Actions\Action::make('atender')
                    ->label('Atender')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Atender Chamado')
                    ->modalWidth('4xl')
                    ->visible(fn (Chamado $record) => $record->status !== 'encerrado')
                    ->mountUsing(fn (Forms\ComponentContainer $form, Chamado $record) => $form->fill($record->toArray()))
                    ->form([
                        Tabs::make('ChamadoTabs')->tabs([
                            Tabs\Tab::make('Informações')->schema([
                                Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('nome')->label('Nome')->disabled(),
                                    Forms\Components\TextInput::make('email')->label('E-mail')->disabled(),
                                ]),
                                Forms\Components\TextInput::make('departamento.nome')->label('Departamento Responsável')->default(fn(Chamado $record) => $record->departamento?->nome)->disabled(),
                                Forms\Components\Textarea::make('descricao')->label('Descrição')->disabled()->rows(4),
                            ]),
                            Tabs\Tab::make('Status e Solução')->schema([
                                Forms\Components\Select::make('status')->label('Status')->options(['aberto' => 'Aberto', 'em_andamento' => 'Em andamento', 'encerrado' => 'Encerrado'])->required()->reactive(),
                                Forms\Components\Textarea::make('solucao')->label('Relato / Solução')->rows(4)->visible(fn (callable $get) => $get('status') === 'encerrado')->required(fn (callable $get) => $get('status') === 'encerrado'),
                                Forms\Components\Repeater::make('itens_usados')->label('Itens Utilizados')->relationship('itensEstoque')->schema([
                                    Forms\Components\Select::make('item_estoque_id')->label('Item de Estoque')->options(ItemEstoque::pluck('nome', 'id'))->searchable()->required(),
                                    Forms\Components\TextInput::make('quantidade')->label('Quantidade')->numeric()->minValue(1)->required(),
                                ])->visible(fn (callable $get) => $get('status') === 'encerrado'),
                            ]),
                        ]),
                    ])
                    ->action(function (Chamado $record, array $data) {
                        $record->fill($data);
                        if ($data['status'] === 'encerrado') {
                            $record->encerrado = true;
                            $record->encerrado_em = now();
                        } else {
                             $record->encerrado = false;
                             $record->encerrado_em = null;
                             $record->solucao = null;
                             $record->itensEstoque()->detach();
                        }
                        $record->save();
                    }),

                Tables\Actions\ViewAction::make('visualizar')
                    ->label('Visualizar')
                    ->modalHeading('Detalhes do Chamado')
                    ->visible(fn (Chamado $record) => $record->status === 'encerrado')
                    ->form([
                        Tabs::make('ViewTabs')->tabs([
                            Tabs\Tab::make('Informações')->schema([
                                Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('nome'),
                                    Forms\Components\TextInput::make('email'),
                                ]),
                                Forms\Components\TextInput::make('departamento.nome')->label('Departamento Responsável'),
                                Forms\Components\Textarea::make('descricao')->label('Descrição'),
                            ]),
                            Tabs\Tab::make('Solução')->schema([
                                Forms\Components\TextInput::make('status'),
                                Forms\Components\Textarea::make('solucao')->label('Relato / Solução'),
                                Forms\Components\Repeater::make('itensEstoque')->relationship()->label('Itens Utilizados')->schema([
                                     Forms\Components\Select::make('item_estoque_id')->label('Item')->options(ItemEstoque::pluck('nome', 'id')),
                                     Forms\Components\TextInput::make('quantidade'),
                                ])->disabled(),
                            ]),
                        ])
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Excluir Selecionados'),
            ])
            // MELHORIA: Agrupamento de dados
            ->groups([
                Group::make('departamento.nome')->label('Departamento'),
                Group::make('status')->label('Status'),
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