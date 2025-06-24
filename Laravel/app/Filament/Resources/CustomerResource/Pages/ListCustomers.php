<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $title = 'Clientes';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
        ];
    }
}
