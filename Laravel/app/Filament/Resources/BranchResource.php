<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Reservation;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $modelLabel = 'sucursal';

    protected static ?string $pluralModelLabel = 'sucursales';

    protected static ?string $navigationLabel = 'Sucursales';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(
        Form $form,
    ): Form {
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
                TextInput::make('address')
                    ->label('Dirección')
                    ->columnSpan(2)
                    ->placeholder('Ingrese una dirección'),
                Map::make('map')
                    ->label('Dirección')
                    ->columnSpan(2)
                    ->required()
                    ->geolocate()
                    ->autocomplete('address')
                    ->autocompleteReverse()
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

    public static function table(
        Table $table,
    ): Table {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('address')
                    ->label('Dirección')
                    ->limit(60)
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (Branch $record) => $record->trashed()),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Branch $record) {
                        $hasPendingReservations = Reservation::whereRelation('branch_product', 'branch_id', $record->id)
                            ->where(function (Builder $q) {
                                $q->where(function (Builder $subQ) {
                                    // Reservas futuras (no retiradas y end_date >= hoy)
                                    $subQ->whereDoesntHave('retired')
                                        ->where('end_date', '>=', now()->format('Y-m-d'));
                                })->orWhere(function (Builder $subQ) {
                                    // Retiradas pero no devueltas
                                    $subQ->whereHas('retired')->whereDoesntHave('returned');
                                });
                            })
                            ->exists();

                        if ($hasPendingReservations) {
                            Notification::make()
                                ->title('No se puede eliminar la sucursal')
                                ->body('Existen reservas activas o retiradas y no devueltas que impiden la eliminación de esta sucursal.')
                                ->danger()
                                ->send();

                            return;
                        }

                        // $branch_products = BranchProduct::where("branch_id", $record->id)->get();
                        // $branch_products->map(function (BranchProduct $branch_product) {
                        //    $branch_product->delete();
                        // });

                        $record->delete();
                    }),
                Tables\Actions\RestoreAction::make()
                    ->label('Restaurar')
                    ->color('danger'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->withTrashed();
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
