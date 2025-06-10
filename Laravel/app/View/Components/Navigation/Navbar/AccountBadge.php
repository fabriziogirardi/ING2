<?php

namespace App\View\Components\Navigation\Navbar;

use App\Models\Person;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccountBadge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Person $person,
        public string $userType = '',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navigation.navbar.account-badge');
    }
}
