<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachResource\Pages;
use App\Filament\Resources\CoachResource\RelationManagers;
use App\Models\Coach;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class CoachResource extends Resource
{
    protected static ?string $model = Coach::class;

    
    protected static ?string $modelLabel = 'Coach';
    protected static ?string $pluralModelLabel = 'Coachs';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // This creates a dropdown that automatically fetches all users from the database!
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Compte Utilisateur lié')
                    ->required(),

                TextInput::make('first_name')
                    ->label('Prénom')
                    ->required()
                    ->maxLength(255),

                TextInput::make('last_name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),

                TextInput::make('specialty')
                    ->label('Spécialité')
                    ->placeholder('ex. Maths, Orientation')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Notice we are using the `full_name` accessor we created in the Model!
                TextColumn::make('full_name')
                    ->label('Nom complet')
                    ->searchable(['first_name', 'last_name']) // Tells Filament how to search the accessor
                    ->sortable(['last_name']),

                TextColumn::make('specialty')
                    ->label('Spécialité')
                    ->searchable(),

                // We can reach through the relationship to display the User's email
                TextColumn::make('user.email')
                    ->label('Email de connexion')
                    ->searchable(),
            ])
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
            'index' => Pages\ListCoaches::route('/'),
            'create' => Pages\CreateCoach::route('/create'),
            'edit' => Pages\EditCoach::route('/{record}/edit'),
        ];
    }
}
