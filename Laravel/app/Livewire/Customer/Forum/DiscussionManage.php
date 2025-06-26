<?php

namespace App\Livewire\Customer\Forum;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

trait DiscussionManage
{
    public $title = '';

    public $content = '';

    public function discussionform(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Titulo')
                    ->placeholder('Escribe aquí el título de la discusión')
                    ->required()
                    ->maxLength(255)
                    ->minLength(1),
                Textarea::make('content')
                    ->required()
                    ->label('Cuerpo de la discusión')
                    ->placeholder('Escribe aquí el contenido de la discusión')
                    ->extraAttributes(['class' => 'overflow-y-auto'])
                    ->minLength(1),
            ])
            ->statePath('');
    }
}
