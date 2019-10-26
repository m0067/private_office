<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class UserPolicy
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->api_token === $model->api_token;

    }

    /**
     * @param  User  $user
     * @param  string  $role
     * @param  bool  $isBlocked
     *
     * @return bool
     */
    public function create(User $user, string $role = '', bool $isBlocked = false): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isManager() && ! empty($role)) {
            return $role === User::ROLES['USER'];
        }

        return $user->isManager() && ! $isBlocked;
    }

    /**
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * @param  User  $user
     * @param  User  $model
     *
     * @return bool
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
