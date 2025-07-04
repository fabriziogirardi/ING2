<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\CancelPolicyProduct;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $data    = $this->data;
        $product = $this->record;

        if (isset($data['cancel_policy'])) {
            if ($data['cancel_policy'] === 'completa') {
                CancelPolicyProduct::create([
                    'product_id'       => $product->id,
                    'cancel_policy_id' => 1,
                ]);
            } elseif ($data['cancel_policy'] === 'parcial') {
                CancelPolicyProduct::create([
                    'product_id'       => $product->id,
                    'cancel_policy_id' => 2,
                ]);
            }
        }
    }
}
