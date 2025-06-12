<?php

namespace App\View\Components\Forms\Input;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $type = 'text',
        public ?string $name = null,
        public ?string $label = null,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $pattern = null,
        public ?string $maxlength = null,
        public ?string $minlength = null,
        public ?string $min = null,
        public ?string $max = null,
        public ?string $step = null,
        public ?string $id = null,
        public ?string $iconLeft = null,
        public bool $required = false,
        public bool $optional = false,
        public bool $readonly = false,
        public bool $disabled = false,
        public ?string $error = null,
        public ?string $helpText = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input.text');
    }
}
