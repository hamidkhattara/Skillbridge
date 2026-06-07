<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Service;
use App\Models\Coach;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Forms\Set;

class BookingResource extends Resource
{
    protected static ?string $modelLabel = 'Session';
    protected static ?string $pluralModelLabel = 'Sessions';
    protected static ?string $navigationGroup = 'Planification';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Détails de la réservation')
                    ->schema([
                        // 1. Select the Coach
                        Select::make('coach_id')
                            ->label('Coach')
                            ->relationship('coach', 'id')
                            ->getOptionLabelFromRecordUsing(fn (Coach $record) => $record->full_name)
                            ->searchable(['first_name', 'last_name']) // <-- ADD THE COLUMNS HERE
                            ->preload() // (Optional) This loads the options immediately before typing
                            ->required(),

                        // 2. Client Name
                        TextInput::make('client_name')
                            ->label('Nom du client')
                            ->required()
                            ->maxLength(255),

                        Grid::make(2)->schema([
                            // 3. Select the Service (This is the reactive magic!)
                            Select::make('service_id')
                                ->label('Prestation')
                                ->relationship('service', 'name', modifyQueryUsing: fn ($query) => $query->active())
                                ->searchable()
                                ->required()
                                ->live() // Tells Filament to listen for changes
                                ->afterStateUpdated(function (Set $set, ?string $state) {
                                    // When the service changes, auto-fill the duration!
                                    if (!$state) return;
                                    $service = Service::find($state);
                                    if ($service) {
                                        $set('duration', $service->default_duration);
                                        // We also reset modality so they don't submit an invalid one
                                        $set('modality', null); 
                                    }
                                }),

                            DateTimePicker::make('starts_at')
                                ->label('Date et heure de début')
                                ->required()
                                ->seconds(false),
                        ]),

                        Grid::make(3)->schema([
                            TextInput::make('duration')
                                ->label('Durée (min)')
                                ->numeric()
                                ->required(),

                            // 4. Reactive Modality Dropdown
                            Select::make('modality')
                                ->label('Modalité')
                                ->required()
                                ->options(function (Get $get) {
                                    // Look at the currently selected service
                                    $serviceId = $get('service_id');
                                    if (!$serviceId) return [];

                                    $service = Service::find($serviceId);
                                    if (!$service) return [];

                                    // Return only the allowed options!
                                    if ($service->modality === 'online') {
                                        return ['online' => 'En ligne'];
                                    }
                                    if ($service->modality === 'in_person') {
                                        return ['in_person' => 'En présentiel'];
                                    }
                                    
                                    // If 'either', they can choose either one
                                    return [
                                        'online' => 'En ligne', 
                                        'in_person' => 'En présentiel'
                                    ]; 
                                }),

                            Select::make('status')
                                ->label('Statut')
                                ->options([
                                    'confirmed' => 'Confirmé',
                                    'cancelled' => 'Annulé',
                                ])
                                ->default('confirmed')
                                ->required(),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Date and Time
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Date et heure')
                    ->dateTime('d/m/Y H:i') // Formats it nicely (e.g., 25/12/2024 14:30)
                    ->sortable(),

                // 2. Client Name
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable(),

                // 3. Reaching through relationships (Dot Notation!)
                Tables\Columns\TextColumn::make('coach.full_name')
                    ->label('Coach')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['last_name']),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Prestation')
                    ->sortable(),

                // 4. Duration
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durée')
                    ->suffix(' min')
                    ->sortable(),

                // 5. Status Badge
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'confirmed' => 'Confirmé',
                        'cancelled' => 'Annulé',
                    }),
            ])
            ->defaultSort('starts_at', 'desc') // Show newest bookings first
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
