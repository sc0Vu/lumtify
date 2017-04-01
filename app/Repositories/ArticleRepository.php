<?php

namespace App\Repositories;

use DB;
use App\Article;
use App\Category;
use App\CategoryRelationship;

class ArticleRepository
{
	/**
	 * Get articles.
	 * 
	 * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int  $page
     * @param  array  $status
     * @param  int  $userId
     * @param  string  $category
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function articles($perPage = 10, $columns = ["*"], $pageName = "page", $page = 1, $status=[Article::STATUS_PUBLISHED], $userId = 0, $category = "")
	{
		if (!is_array($status)) {
			$status = [$status];
		}
        if (preg_match("/[\w]+/", $category)) {
            $categories = explode(",", $category);
            $relationships = Category::select("id")->whereIn("slug", $categories)->with(["articles"])->get()->pluck("articles");
            $articleIds = [];

            foreach ($relationships as $relation) {
                $articleIds = array_merge($articleIds, $relation->pluck("article_id")->all());
            }
        } else {
            $category = "";
        }
        if (empty($category)) {
            if (empty($userId)) {
                return Article::whereIn("status", $status)->with(["author", "categories.category"])->paginate($perPage, $columns, $pageName, $page);
            }
            return Article::where("user_id", $userId)->whereIn("status", $status)
                            ->with(["author", "categories.category"])->paginate($perPage, $columns, $pageName, $page);
        } else {
            if (empty($userId)) {
                return Article::whereIn("status", $status)->whereIn("id", $articleIds)->with(["author", "categories.category"])->paginate($perPage, $columns, $pageName, $page);
            }
            return Article::where("user_id", $userId)->whereIn("status", $status)->whereIn("id", $articleIds)
                            ->with(["author", "categories.category"])->paginate($perPage, $columns, $pageName, $page);
        }
	}

	/**
     * Create article.
     * 
     * @param  array  $data
     * @return boolean
     */
    public function create($data)
    {
        DB::beginTransaction();
        $article = new Article;
        $article->user_id = $data["user_id"];
        $article->title = $data["title"];
        $article->link = $data["link"];
        $article->short_description = $data["short_description"];
        $article->content = $data["content"];
        $article->thumbnail = $data["thumbnail"];
        $article->status = $data["status"];
        
        try {
            if ($article->save()) {
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
	 * Get article.
	 * 
	 * @param  string  $link
	 * @param  integer  $status
     * @param  int  $userId
	 * @return array || null
	 */
	public function read($link, $status=Article::STATUS_PUBLISHED, $userId = 0)
	{
		if (!is_array($status)) {
			$status = [$status];
		}
        if (empty($userId)) {
            return Article::where("link", $link)->whereIn("status", $status)->with(["author", "categories.category"])->first();
        }
		return Article::where("link", $link)->where("user_id", $userId)->whereIn("status", $status)->with(["author", "categories.category"])->first();
	}

	/**
     * Update article.
     *
     * @param  \App\Article  $article
     * @param  array  $data
     * @return boolean
     */
    public function update(Article $article, $data)
    {
    	if (isset($data["title"])) {
    		$article->title = $data["title"];
    	}
    	if (isset($data["link"])) {
    		$article->link = $data["link"];
    	}
    	if (isset($data["short_description"])) {
    		$article->short_description = $data["short_description"];
    	}
    	if (isset($data["content"])) {
    		$article->content = $data["content"];
    	}
    	if (isset($data["thumbnail"])) {
    		$article->thumbnail = $data["thumbnail"];
    	}
    	if (isset($data["status"])) {
    		$article->status = $data["status"];
    	}
        DB::beginTransaction();

        try {
            if ($article->save()) {
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
     * Delete article.
     * 
     * @param  \App\Article  $article
     * @return boolean
     */
    public function delete(Article $article)
    {
    	DB::beginTransaction();

    	try {
            if ($article->delete()) {
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