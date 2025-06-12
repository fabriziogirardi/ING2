<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use Cheesegrits\FilamentGoogleMaps\Columns\MapColumn;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

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
                    ->unique()
                    ->minLength(3)
                    ->maxLength(255)
                    ->string(),
                Map::make('map')
                    ->label('Dirección')
                    ->columnSpan(2)
                    ->required()
                    ->geolocate()
                    ->defaultZoom(15)
                    ->defaultLocation(fn ($get) => $get('default_location') ?: ['-34.921346366044', '-57.954496631585'])
                    ->draggable()
                    ->clickable()
                    ->mapControls([
                        'mapTypeControl'    => true,
                        'scaleControl'      => true,
                        'streetViewControl' => true,
                        'rotateControl'     => true,
                        'fullscreenControl' => true,
                    ])
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if (isset($state['lat'])) {
                            $set('latitude', $state['lat']);
                        }
                        if (isset($state['lng'])) {
                            $set('longitude', $state['lng']);
                        }

                        if (isset($state['lat'], $state['lng'])) {
                            $apiKey   = config('credentials.google_maps.private_api_key');
                            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                                'latlng' => "{$state['lat']},{$state['lng']}",
                                'key'    => $apiKey,
                            ]);

                            if ($response->successful()) {
                                $results = $response->json('results');
                                if (! empty($results)) {
                                    $first = $results[0];

                                    // Dirección completa
                                    $set('address', $first['formatted_address'] ?? null);

                                    // place_id
                                    if (isset($first['place_id'])) {
                                        $set('place_id', $first['place_id']);
                                    }
                                }
                            }
                        }

                    }),
                Hidden::make('default_location'),
                Hidden::make('address'),
                Hidden::make('place_id'),
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
                TextColumn::make('address')->label('Direccion')->limit(60),
                // MapColumn::make('address')->label('Direccion'),
                TextColumn::make('description')
                    ->label('Descripcion')
                    ->limit(50),
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
