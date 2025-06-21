<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\Wishlist;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Livewire\Component;

class WishlistManage extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Wishlist::where('customer_id', auth('customer')->user()->id))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('sublists_count')
                    ->counts('sublists')
                    ->label('Cantidad de sublistas'),
            ])
            ->recordUrl(
                fn ($record) => route('customer.subwishlist', ['wishlist' => $record->id])
            )
            ->headerActions([
                CreateAction::make()
                    ->label('Crear nueva lista')
                    ->color('success')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nombre de la lista')
                            ->rule(function () {
                                $customerId = auth('customer')->user()->id;

                                return Rule::unique('wishlists', 'name')
                                    ->where('customer_id', $customerId);
                            }),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['customer_id'] = auth('customer')->user()->id;

                        return $data;
                    }),
            ])
            ->actions([
                Action::make('delete')
                    ->icon('heroicon-m-trash')
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->disabled(fn (Wishlist $record) => $record->sublists()->count() > 0)
                    ->action(fn (Wishlist $record) => $record->delete()),
            ]);
    }

    public function render()
    {
        return view('livewire.wishlist.wishlist');
    }
}
