<?php

namespace App\Policies;

use App\Models\FooterElement;
use App\Models\Manager;

class FooterElementPolicy
{
    /**
     * Determine whether the manager can view any models.
     */
    public function viewAny(Manager $manager): bool
    {
        return true;
    }

    /**
     * Determine whether the manager can view the model.
     */
    public function view(Manager $manager, FooterElement $footerElement): bool
    {
        return true;
    }

    /**
     * Determine whether the manager can create models.
     */
    public function create(Manager $manager): bool
    {
        return true;
    }

    /**
     * Determine whether the manager can update the model.
     */
    public function update(Manager $manager, FooterElement $footerElement): bool
    {
        return true;
    }

    /**
     * Determine whether the manager can delete the model.
     */
    public function delete(Manager $manager, FooterElement $footerElement): bool
    {
        return true;
    }

    /**
     * Determine whether the manager can restore the model.
     */
    public function restore(Manager $manager, FooterElement $footerElement): bool
    {
        return true;
    }

    /**
     * Determine whether the manager can permanently delete the model.
     */
    public function forceDelete(Manager $manager, FooterElement $footerElement): bool
    {
        return true;
    }
}
