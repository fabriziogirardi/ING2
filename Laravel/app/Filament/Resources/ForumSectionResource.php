<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumSectionResource\Pages;
use App\Models\ForumSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumSectionResource extends Resource
{
    protected static ?string $model = ForumSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $pluralModelLabel = 'Secciónes del Foro';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique()
                    ->maxLength(255)
                    ->label('Nombre'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre'),
                Tables\Columns\TextColumn::make('discussions_count')->label('N° de Discusiones')
                    ->counts('discussions'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->successNotificationTitle(__('manager/section.deleted'))
                    ->failureNotification(
                        Notification::make()
                            ->title('No se puede eliminar')
                            ->body('Esta sección del foro tiene discusiones asociadas.')
                            ->danger(),
                    )
                    ->before(function ($record, $action) {
                        if ($record->discussions()->exists()) {
                            $action->failure();
                            return false;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No hay secciones del foro registradas');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListForumSections::route('/'),
            'create' => Pages\CreateForumSection::route('/create'),
            'edit'   => Pages\EditForumSection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
