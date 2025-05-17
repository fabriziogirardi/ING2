<?php

namespace App\Auth;

use Closure;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Arrayable;

class EloquentSplitTableUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     */
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials): ?Authenticatable
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return null;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } elseif ($value instanceof Closure) {
                $value($query);
            } else {
                if ($key === 'email') {
                    $query->whereRelation('person', 'email', $value);
                } else {
                    // For other fields, we can directly apply the where clause
                    // to the query.
                    $query->where($key, $value);
                }
            }
        }

        return $query->first();
    }
}
