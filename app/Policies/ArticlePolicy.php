<?php

namespace App\Policies;

// use Illuminate\Auth\Access\HandlesAuthorization;
use Cache;
use App\User;
use App\Article;

class ArticlePolicy
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
     * The read article policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return boolean
     */
    // public function read(User $user, Article $article)
    // {
    //     if ($user->isEditor()) {
    //         return true;
    //     }
    //     return false;
    // }

    /**
     * The create article policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return boolean
     */
    public function create(User $user, Article $article)
    {
        if ($user->isEditor()) {
            return true;
        }
        return false;
    }

    /**
     * The update article policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return boolean
     */
    public function update(User $user, Article $article)
    {
        if ($user->isEditor() && ($article->user_id == $user->id)) {
            return true;
        }
        return false;
    }

    /**
     * The delete article policy.
     * 
     * @param  \App\User  $user
     * @param  \App\Article  $article
     * @return boolean
     */
    public function delete(User $user, Article $article)
    {
        if ($user->isEditor() && ($article->user_id == $user->id)) {
            return true;
        }
        return false;
    }
}
