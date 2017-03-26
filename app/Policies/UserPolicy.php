<?php

namespace App\Policies;

// use Illuminate\Auth\Access\HandlesAuthorization;
use Cache;
use App\User;

class UserPolicy
{
    /**
     * Uncomment to use:
     * allow($message)
     * deny($message)
     */
    // use HandlesAuthorization;
    
    /**
     * user roles
     * 
     * @var App\RoleAssign
     */
    protected $roles;

    /**
     * Create a new policy instance.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        if (empty($this->roles)) {
            $key = sha1(sprintf("%s_roles", $user->uid));

            if (Cache::has($key)) {
                $this->roles = Cache::get($key);
            } else {
                $roles = $user->roles()->with('role')->get();
                $this->roles = $roles;
                Cache::put($key, $roles, 60);
            }
        }
    }

    /**
     * before all check
     * 
     * @param  \App\User  $user
     * @param  string  $ability
     * @return boolean
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            if ($ability !== 'delete') {
                return true;
            }
        }
    }

    /**
     * The users policy.
     * 
     * @param  \App\User  $user
     * @param  \App\User  $userChecked
     * @return boolean
     */
    public function users(User $user, User $userChecked)
    {
        return false;
    }
    
    /**
     * The read user policy.
     * 
     * @param  \App\User  $user
     * @param  \App\User  $userChecked
     * @return boolean
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
     * @param  \App\User  $user
     * @param  \App\User  $userChecked
     * @return boolean
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
     * @param  \App\User  $user
     * @param  \App\User  $userChecked
     * @return boolean
     */
    public function delete(User $user, User $userChecked)
    {
        if ($user->isAdmin() && ($user->id !== $userChecked->id)) {
            return true;
        }
        return false;
    }
}
