<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Submit extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public ?string $text = null,
        public ?string $value = null,
        public ?string $iconLeft = null,
        public ?string $iconRight = null,
        public string $type = 'primary',
        public string $primary = 'relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white',
        public string $danger = 'relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-rose-400 to-red-600 group-hover:from-rose-400 group-hover:to-red-600 hover:text-white',
        public string $alert = 'relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-yellow-300 to-amber-400 group-hover:from-yellow-300 group-hover:to-amber-400 hover:text-white',
        public string $success = 'relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-lime-500 to-green-600 group-hover:from-lime-500 group-hover:to-green-600 hover:text-white',
        public bool $disabled = false,
        public bool $submit = true,
        public bool $fullWidth = false,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.submit');
    }
}
