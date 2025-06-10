<?php

namespace App\Filament\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerRelationManager extends RelationManager
{
    protected static string $relationship = 'customer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rating')
                    ->numeric()
                    ->label('Calificación inicial')
                    ->readOnly(true)
                    ->default(3.0)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Puntaje')
            ->columns([
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->label('Puntaje'),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Activo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de creación')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
