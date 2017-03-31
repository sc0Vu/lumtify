<?php

namespace App\Repositories;

use DB;
use Cache;
use App\Category;

class CategoryRepository
{
    /**
     * Get categories.
     * 
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int  $page
     * @return \App\Category
     */
    public function categories($perPage = 10, $columns = ['*'], $pageName = 'page', $page = 1)
    {
        return Category::paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Create category.
     * 
     * @param  array  $data
     * @return boolean
     */
    public function create($data)
    {
        $category = new Category;

        $category->name = $data["name"];
        $category->slug = $data["slug"];
        $category->parent_id = @$data["parent_id"];
        $category->child_id = @$data["child_id"];
        
        DB::beginTransaction();
        
        try {
            if ($category->save()) {
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Get category.
     * 
     * @param  string  $slug
     * @return \App\User
     */
    public function read($slug="")
    {
        return Category::where("slug", $slug)->first();
    }

    /**
     * Update category.
     *
     * @param  \App\Category  $category
     * @param  array  $data
     * @return mixed
     */
    public function update(Category $category, $data)
    {
        if (isset($data["name"])) {
            $category->name = $data["name"];
        }
        if (isset($data["slug"])) {
            $category->slug = $data["slug"];
        }
        if (isset($data["parent_id"])) {
            $category->parent_id = $data["parent_id"];
        }
        if (isset($data["child_id"])) {
            $category->child_id = $data["child_id"];
        }
        DB::beginTransaction();

        try {
            if ($category->save()) {
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return false;
        }
    }
    
    /**
     * Delete category.
     * 
     * @param  \App\Category  $category
     * @return boolean
     */
    public function delete(Category $category)
    {
        DB::beginTransaction();
        
        try {
            if ($category->delete()) {
                DB::commit();
                return true;
            }
            DB::rollback();
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return false;
        }
    }
}