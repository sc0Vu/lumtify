<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Article;

class ArticlePolicy
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
            return true;
        }
        if (empty($this->roles)) {
            $this->roles = $user->roles()->with('role')->get();
        }
    }
    
    /**
     * The read article policy.
     * 
     * @param  User  $user
     * @param  Article  $article
     * @return  boolean
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
     * @param  User  $user
     * @param  Article  $article
     * @return  boolean
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
     * @param  User  $user
     * @param  Article  $article
     * @return  boolean
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
     * @param  User  $user
     * @param  Article  $article
     * @return  boolean
     */
    public function delete(User $user, Article $article)
    {
        if ($user->isEditor() && ($article->user_id == $user->id)) {
            return true;
        }
        return false;
    }
}
