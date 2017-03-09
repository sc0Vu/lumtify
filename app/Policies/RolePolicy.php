<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Role;

class RolePolicy
{
    use HandlesAuthorization;
    
    /**
     * user roles
     * 
     * @var App\RoleAssign
     */
    protected $roles;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    // }

    /**
     * before all check
     * 
     * @param App\User $user
     * @param string $ability
     * @return boolean
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
        if (empty($this->roles)) {
            $this->roles = $user->roles()->with('role')->get();
        }
    }

    /**
     * The roles policy.
     * 
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function roles(User $user, Role $role)
    {
        return false;
    }
    
    /**
     * The read role policy.
     * 
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function read(User $user, Role $role)
    {
        return false;
    }

    /**
     * The update role policy.
     * 
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function update(User $user, Role $role)
    {
        return false;
    }

    /**
     * The delete role policy.
     * 
     * @param User $user
     * @param Role $role
     * @return boolean
     */
    public function delete(User $user, Role $role)
    {
        return false;
    }
}
