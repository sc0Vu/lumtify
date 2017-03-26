<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cache;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;
    
    /**
     * The activated status
     */
    const STATUS_ACTIVATED = 1;
    
    /**
     * The banned status
     */
    const STATUS_BANNED = 2;

    /**
     * The table
     * 
     * @var string
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "uid", "name", "email", "password", "status"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        "id", "password", "status"
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
    /**
     * The roles relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany("App\RoleAssign", "user_id", "id");
    }

    /**
     * The articles relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany("App\Article", "user_id", "id");
    }

    /**
     * The user is admin.
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        $key = sha1(sprintf("%s_roles", $this->uid));

        if (Cache::has($key)) {
            $roles = Cache::get($key);
        } else {
            $roles = $this->roles()->with('role')->get();
            Cache::put($key, $roles, 60);
        }
        foreach ($roles as &$role) {
            if ($role->role->name === "admin") {
                return true;
            }
        }
        return false;
    }

    /**
     * The user is editor.
     * 
     * @return boolean
     */
    public function isEditor()
    {
        $key = sha1(sprintf("%s_roles", $this->uid));

        if (Cache::has($key)) {
            $roles = Cache::get($key);
        } else {
            $roles = $this->roles()->with('role')->get();
            Cache::put($key, $roles, 60);
        }
        foreach ($roles as &$role) {
            if ($role->role->name === "editor") {
                return true;
            }
        }
        return false;
    }

    /**
     * Merge data.
     * 
     * @param  array  $data
     * @return void
     */
    public function mergeData($data)
    {
        $this->attributes = array_merge($this->attributes, $data);
    }
}
