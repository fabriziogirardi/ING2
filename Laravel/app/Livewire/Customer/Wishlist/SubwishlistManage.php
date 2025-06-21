<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\Wishlist;
use App\Models\WishlistSublist;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use function Illuminate\Support\php_binary;

class SubwishlistManage extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    public Wishlist $wishlist;

    public function mount(Wishlist $wishlist)
    {
        $this->wishlist = $wishlist;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(WishlistSublist::where('wishlist_id', $this->wishlist->id))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Cantidad de items'),
            ])
            ->recordUrl(
                fn ($record) => route('customer.itemslist', ['wishlist' => $this->wishlist->id,'subwishlist' => $record->id])
            )
            ->headerActions([
                Action::make('back_to_wishlist')
                    ->label('Volver')
                    ->icon('heroicon-m-arrow-left')
                    ->color('info')
                    ->url(route('customer.wishlist')),
                CreateAction::make()
                    ->label('Crear nueva sublista')
                    ->color('success')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nombre de la sublista')
                            ->rule(function () {
                                // Use $this->wishlist->id to scope uniqueness to the current wishlist
                                return \Illuminate\Validation\Rule::unique('wishlist_sublists', 'name')
                                    ->where('wishlist_id', $this->wishlist->id);
                            }),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['wishlist_id'] = $this->wishlist->id;

                        return $data;
                    }),
            ])
            ->actions([
                Action::make('delete')
                    ->icon('heroicon-m-trash')
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->disabled(fn (WishlistSublist $record) => $record->items()->count() > 0)
                    ->action(fn (WishlistSublist $record) => $record->delete())
            ]);
    }

    public function render()
    {
        return view('livewire.wishlist.subwishlist');
    }
}
