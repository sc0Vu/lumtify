<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The primary key
     * 
     * @var string
     */
    public $primary = "id";
    
    /**
     * The table
     * 
     * @var string
     */
    protected $table = "roles";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        "id"
    ];

    /**
     * The users relation
     * 
     * @return App\RoleAssign
     */
    public function users()
    {
        return $this->hasMany("App\RoleAssign", "role_id", "role_id")->with("user");
    }
}
