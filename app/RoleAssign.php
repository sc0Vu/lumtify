<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAssign extends Model
{
    /**
     * The timestamps
     * 
     * @var boolean
     */
    public $timestamps = false;
    
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo("App\Role", "role_id", "id");
    }

    /**
     * The user relation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo("App\User", "user_id", "id");
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        if (is_null($this->role_id) || is_null($this->user_id)) {
            throw new Exception('No role id or user id defined on model.');
        }

        if ($this->exists) {
            if ($this->fireModelEvent('deleting') === false) {
                return false;
            }

            // Here, we'll touch the owning models, verifying these timestamps get updated
            // for the models. This will allow any caching to get broken on the parents
            // by the timestamp. Then we will go ahead and delete the model instance.
            $this->touchOwners();

            $this->where("role_id", $this->role_id)->where("user_id", $this->user_id)->delete();

            $this->exists = false;

            // Once the model has been deleted, we will fire off the deleted event so that
            // the developers may hook into post-delete operations. We will then return
            // a boolean true as the delete is presumably successful on the database.
            $this->fireModelEvent('deleted', false);

            return true;
        }
    }
}
