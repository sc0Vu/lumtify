<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryRelationship extends Model
{
    /**
     * The table
     * 
     * @var string
     */
    protected $table = "category_relationships";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "article_id", "category_id"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        "article_id", "category_id"
    ];

    /**
     * The article relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo("App\Article", "article_id", "id");
    }

    /**
     * The category relation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo("App\Category", "category_id", "id");
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
        if (is_null($this->category_id) || is_null($this->article_id)) {
            throw new Exception('No category id or article id defined on model.');
        }

        if ($this->exists) {
            if ($this->fireModelEvent('deleting') === false) {
                return false;
            }

            // Here, we'll touch the owning models, verifying these timestamps get updated
            // for the models. This will allow any caching to get broken on the parents
            // by the timestamp. Then we will go ahead and delete the model instance.
            $this->touchOwners();

            $this->where("category_id", $this->category_id)->where("article_id", $this->article_id)->delete();

            $this->exists = false;

            // Once the model has been deleted, we will fire off the deleted event so that
            // the developers may hook into post-delete operations. We will then return
            // a boolean true as the delete is presumably successful on the database.
            $this->fireModelEvent('deleted', false);

            return true;
        }
    }
}
