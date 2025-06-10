<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $modelLabel = 'sucursal';

    protected static ?string $pluralModelLabel = 'sucursales';

    protected static ?string $navigationLabel = 'Sucursales';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->columnSpan(2)
                    ->required()
                    ->minLength(3)
                    ->maxLength(255)
                    ->string(),
                Map::make('address')
                    ->label('Dirección')
                    ->columnSpan(2)
                    ->required()
                    ->geolocate()
                    ->defaultZoom(15)
                    ->draggable()
                    ->clickable()
                    ->mapControls([
                        'mapTypeControl'    => true,
                        'scaleControl'      => true,
                        'streetViewControl' => true,
                        'rotateControl'     => true,
                        'fullscreenControl' => true,
                    ])
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (isset($state['lat'])) {
                            $set('latitude', $state['lat']);
                        }
                        if (isset($state['lng'])) {
                            $set('longitude', $state['lng']);
                        }
                    }),
                Hidden::make('latitude'),
                Hidden::make('longitude'),
                RichEditor::make('description')
                    ->columnSpan(2)
                    ->label('Descripción')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'link',
                        'bulletList',
                        'numberList',
                        'blockquote',
                        'codeBlock',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre'),
                TextColumn::make('address')->label('Direccion'),
                TextColumn::make('description')
                    ->label('Descripcion')
                    ->limit(50)
                    ->toggleable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit'   => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
