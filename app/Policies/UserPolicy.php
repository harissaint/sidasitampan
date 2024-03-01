<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function isAdmin(User $user)
    {
        return $user->group->nama == 'admin';
    }

    public function isTapd(User $user)
    {
        return $user->group->nama == 'TAPD';
    }

    public function isSkpd(User $user)
    {
        return $user->group->nama == 'SKPD';
    }
}
