<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $modelLabel = 'Prestation';
    protected static ?string $pluralModelLabel = 'Prestations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Using a Section makes the form look professional with a white card background
                Section::make('Détails de la prestation')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->columnSpanFull(), // Makes it take up the whole width

                        // Using a Grid to put these 3 fields side-by-side
                        Grid::make(3)
                            ->schema([
                                TextInput::make('default_duration')
                                    ->label('Durée par défaut (min)')
                                    ->numeric()
                                    ->default(60)
                                    ->minValue(15)
                                    ->maxValue(240)
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Prix')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('€'),

                                ColorPicker::make('color')
                                    ->label('Couleur')
                                    ->hex() // Enforces valid hex code
                                    ->default('#445566'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('billing_unit')
                                    ->label('Unité de facturation')
                                    ->options([
                                        'session' => 'Par session',
                                        'hour' => 'Par heure',
                                    ])
                                    ->default('session')
                                    ->required(),

                                Select::make('modality')
                                    ->label('Modalité')
                                    ->options([
                                        'online' => 'En ligne',
                                        'in_person' => 'En présentiel',
                                        'either' => 'Au choix',
                                    ])
                                    ->default('online')
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Actif')
                                    ->default(true),

                                Toggle::make('is_recurring_default')
                                    ->label('Récurrence par défaut')
                                    ->default(false),
                            ]),
                    ])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('default_duration')
                    ->label('Durée')
                    ->suffix(' min')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Prix')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('modality')
                    ->label('Modalité')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'info',
                        'in_person' => 'success',
                        'either' => 'warning',
                    }),

                ToggleColumn::make('is_active')
                    ->label('Actif'),

                ColorColumn::make('color')
                    ->label('Couleur'),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Éditer'),
                
                Tables\Actions\ReplicateAction::make()
                    ->label('Dupliquer')
                    ->color('warning') // Gives it a nice orange color to stand out
                    ->mutateRecordDataUsing(function (array $data): array {
                        // 1. Append "(copie)" to the original name
                        $data['name'] = $data['name'] . ' (copie)';
                        
                        // 2. Force the new copy to be inactive
                        $data['is_active'] = false;
                        
                        return $data;
                    })
                    ->successNotificationTitle('Prestation dupliquée avec succès.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // THIS IS THE CRUCIAL PART THAT WAS MISSING!
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}