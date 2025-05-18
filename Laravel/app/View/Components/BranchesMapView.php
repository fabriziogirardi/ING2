<?php

namespace App\View\Components;

use App\Models\Branch;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class BranchesMapView extends Component
{
    /**
     * Create a new component instance.
     */
    public Collection|Branch|null $branches;
    public string $api_key;

    public function __construct($branches = null)
    {
        $this->branches = $branches;
        $this->api_key = config('credentials.google_maps.public_api_key');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.branches-map-view');
    }
}
