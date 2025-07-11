<?php

namespace App\View\Components\Elements;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LinkText extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $href = '',
        public string $text = '',
        public string $iconLeft = '',
        public string $iconRight = '',
        public string $type = 'primary',
        public string $primary = 'text-blue-600 hover:text-blue-900    ',
        public string $danger = 'text-red-600 hover:text-red-900    ',
        public string $alert = 'text-yellow-600 hover:text-yellow-900    ',
        public string $target = '_self',
        public bool $disabled = false,
        public ?string $title = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.elements.link-text');
    }
}
