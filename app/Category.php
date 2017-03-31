<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
    protected $table = "categories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "parent_id", "children_id", "slug", "name"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        "id", "parent__id", "children_id"
    ];

    /**
     * The parent relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo("App\Category", "parent_id", "id");
    }

    /**
     * The child relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function child()
    {
        return $this->belongsTo("App\Category", "parent_id", "id");
    }

    /**
     * The articles relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany("App\CategoryRelationship", "id", "category_id")->with("article");
    }
}
