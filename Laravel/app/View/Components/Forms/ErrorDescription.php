<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ErrorDescription extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $message = null,
        public ?string $helpText = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.error-description');
    }
}
