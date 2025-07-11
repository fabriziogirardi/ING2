<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Manager|Customer|Employee $user): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Manager|Customer|Employee $user, Person $person): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Manager|Customer|Employee $user): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Manager|Customer|Employee $user, Person $person): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Manager|Customer|Employee $user, Person $person): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Manager|Customer|Employee $user, Person $person): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Manager|Customer|Employee $user, Person $person): bool
    {
        return $user instanceof Manager || $user instanceof Employee;
    }
}
