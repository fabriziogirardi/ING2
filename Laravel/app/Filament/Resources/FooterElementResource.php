<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterElementResource\Pages;
use App\Models\FooterElement;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Layout;
use Guava\FilamentIconPicker\Tables\IconColumn;

class FooterElementResource extends Resource
{
    protected static ?string $model = FooterElement::class;

    protected static ?string $modelLabel = 'Red Social';

    protected static ?string $pluralModelLabel = 'Redes Sociales';

    protected static ?string $navigationLabel = 'Redes Sociales';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->columnSpan(2)
                    ->required(),
                IconPicker::make('icon')
                    ->label('Ícono')
                    ->columns(6)
                    ->allowedIcons(allowedIcons: [
                        'heroicon-o-globe-alt',
                        'heroicon-o-phone',
                        'heroicon-o-envelope',
                        'heroicon-o-map-pin',
                        'heroicon-o-chat-bubble-left-right',
                        'heroicon-o-share',
                        'heroicon-o-link',
                        'fab-facebook',
                        'fab-twitter',
                        'fab-instagram',
                        'fab-linkedin',
                        'fab-youtube',
                        'fab-whatsapp',
                        'fab-telegram',
                        'fab-tiktok',
                        'fab-pinterest',
                        'fab-snapchat',
                    ])
                    ->preload()
                    ->required(),
                TextInput::make('text')
                    ->label('Texto/Enlace')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('icon')->label('Icono'),
                TextColumn::make('title')->label('Título'),
                TextColumn::make('text')->label('Texto/Enlace'),
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
            'index'  => Pages\ListFooterElements::route('/'),
            'create' => Pages\CreateFooterElement::route('/create'),
            'edit'   => Pages\EditFooterElement::route('/{record}/edit'),
        ];
    }
}
