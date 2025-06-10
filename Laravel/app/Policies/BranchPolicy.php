<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\Manager;
use App\Models\User;

class BranchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Manager $manager): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Manager $manager, Branch $branch): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Manager $manager): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Manager $manager, Branch $branch): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Manager $manager, Branch $branch): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Manager $manager, Branch $branch): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Manager $manager, Branch $branch): bool
    {
        return true;
    }
}
