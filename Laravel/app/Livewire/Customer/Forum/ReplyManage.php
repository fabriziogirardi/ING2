<?php

namespace App\Livewire\Customer\Forum;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

trait ReplyManage
{
    public function replyForm(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('content')
                    ->required()
                    ->hiddenLabel()
                    ->placeholder('Escribe aquí el contenido de la respuesta')
                    ->extraAttributes(['class' => 'overflow-y-auto'])
                    ->minLength(1)
                    ->maxLength(1000),
            ]);
    }
}
