<?php

namespace App\Livewire;

use App\Models\ProductBrand;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ContractView;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;

class ListProductBrands extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductBrand::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // Define your table filters here
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
                //                Action::make('delete')
                //                    ->requiresConfirmation()
                //                    ->action(fn (ProductBrand $record) => $record->delete()),
            ]);
    }

    public function render(): ContractView|Application|Factory|View
    {
        return view('livewire.list-product-brands');
    }
}
