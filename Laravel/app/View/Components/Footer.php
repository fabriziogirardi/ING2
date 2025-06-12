<?php

namespace App\View\Components;

use App\Models\FooterElement;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     */
    public Collection|FooterElement|null $footerElements;

    public function __construct()
    {
        $this->footerElements = FooterElement::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer');
    }
}
