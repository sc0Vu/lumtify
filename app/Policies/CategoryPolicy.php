<?php

namespace App\Policies;

// use Illuminate\Auth\Access\HandlesAuthorization;
use Cache;
use App\User;
use App\Category;

class CategoryPolicy
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
            return true;
        }
    }

    /**
     * The categories policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Category  $category
     * @return boolean
     */
    // public function categories(User $user, Category $category)
    // {
    //     return true;
    // }
    
    /**
     * The read category policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Category  $category
     * @return boolean
     */
    // public function read(User $user, Category $category)
    // {
    //     if ($user->isEditor()) {
    //         return true;
    //     }
    //     return false;
    // }

    /**
     * The create category policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Category  $category
     * @return boolean
     */
    public function create(User $user, Category $category)
    {
        if ($user->isEditor()) {
            return true;
        }
        return false;
    }

    /**
     * The update category policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Category  $category
     * @return boolean
     */
    public function update(User $user, Category $category)
    {
        if ($user->isEditor()) {
            return true;
        }
        return false;
    }

    /**
     * The delete category policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Category  $category
     * @return boolean
     */
    public function delete(User $user, Category $category)
    {
        if ($user->isEditor()) {
            return true;
        }
        return false;
    }
}
