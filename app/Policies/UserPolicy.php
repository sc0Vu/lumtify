<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class UserPolicy
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
     * @param  App\User $user
     * @param   string $ability
     * @return  boolean
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            if ($ability !== 'delete') {
                return true;
            }
        }
        if (empty($this->roles)) {
            $this->roles = $user->roles()->with('role')->get();
        }
    }
    
    /**
     * The read user policy.
     * 
     * @param  User  $user
     * @param  User  $userChecked
     * @return  boolean
     */
    public function read(User $user, User $userChecked)
    {
        if ($user->id == $userChecked->id) {
            return true;
        }
        return false;
    }

    /**
     * The update user policy.
     * 
     * @param  User  $user
     * @param  User  $userChecked
     * @return  boolean
     */
    public function update(User $user, User $userChecked)
    {
        if ($user->id == $userChecked->id) {
            return true;
        }
        return false;
    }

    /**
     * The delete user policy.
     * 
     * @param  User  $user
     * @param  User  $userChecked
     * @return  boolean
     */
    public function delete(User $user, User $userChecked)
    {
        if ($user->isAdmin() && ($user->id !== $userChecked->id)) {
            return true;
        }
        return false;
    }
}
