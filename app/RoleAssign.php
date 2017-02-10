<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAssign extends Model
{
    /**
     * The primary key
     * 
     * @var string
     */
    // public $primary = "";
    
    /**
     * The table
     * 
     * @var string
     */
    protected $table = "role_assigns";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    // ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        "user_id", "role_id"
    ];

    /**
     * The role relation
     * 
     * @return App\Role
     */
    public function role()
    {
        return $this->belongsTo("App\Role", "role_id", "id");
    }

    /**
     * The user relation
     * 
     * @return App\User
     */
    public function user()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }
}
