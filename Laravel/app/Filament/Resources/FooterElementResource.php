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
use Guava\FilamentIconPicker\Tables\IconColumn;

class FooterElementResource extends Resource
{
    protected static ?string $model = FooterElement::class;

    protected static ?string $modelLabel = 'enlace';

    protected static ?string $pluralModelLabel = 'enlaces';

    protected static ?string $navigationLabel = 'Enlaces del footer';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                IconPicker::make('icon')
                    ->label('Ícono')
                    ->columns(6)
                    ->allowedIcons(allowedIcons: [
                        'heroicon-o-globe-alt',
                        'heroicon-o-envelope',
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
                TextInput::make('link')
                    ->label('Link')
                    ->unique()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                IconColumn::make('icon')->label('Icono'),
                TextColumn::make('link')->label('Link'),
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
