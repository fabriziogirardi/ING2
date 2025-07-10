<?php

namespace App\Livewire\Customer\Forum;

use App\Models\ForumSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

trait DiscussionManage
{
    public $title = '';

    public $content = '';

    public $section = '';

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
                    ->extraAttributes([
                        'class' => 'max-h-64 overflow-y-auto w-full',
                    ])
                    ->minLength(1),
                Select::make('section')
                    ->label('Sección')
                    ->placeholder('Selecciona una sección')
                    ->options(
                        ForumSection::all()->pluck('name', 'id')->mapWithKeys(
                            fn ($name, $id) => [$id => ucfirst($name)]
                        )->toArray()
                    )
                    ->required(),
            ])
            ->columns(1)
            ->statePath('');
    }
}
