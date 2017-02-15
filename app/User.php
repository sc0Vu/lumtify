<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
     * The user is admin.
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        $roles = $this->roles()->with("role")->get();
        $isAdmin = false;

        foreach ($roles as &$role) {
            if ($role->role->name === "admin") {
                $isAdmin = true;
                break;
            }
        }
        return $isAdmin;
    }

    /**
     * The user is editor.
     * 
     * @return boolean
     */
    public function isEditor()
    {
        $roles = $this->roles()->with("role")->get();
        $isEditor = false;

        foreach ($roles as &$role) {
            if ($role->role->name === "editor") {
                $isEditor = true;
                break;
            }
        }
        return $isEditor;
    }

    /**
     * Merge data.
     * 
     * @param array $data
     * @return void
     */
    public function mergeData($data)
    {
        $this->attributes = array_merge($this->attributes, $data);
    }
}
