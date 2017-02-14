<?php

namespace App\Repositories;

use App\Article;

class ArticleRepository
{
	/**
	 * get single article
	 * 
	 * @param  string $link
	 * @param  integer $status
	 * @param  boolean $withAuthor
	 * @return array || null
	 */
	public function getArticle($link, $status=Article::STATUS_PUBLISHED, $withAuthor=false)
	{
		$query = Article::where("link", $link)->where("status", $status);
		                    
        if ($withAuthor) {
        	$query->with("author");
        }
		return $query->first();
	}

	/**
	 * get articles
	 * 
	 * @param  integer $page
	 * @param  integer $limit
	 * @param  array  $status
	 * @param  array  $columns
	 * @param  boolean $withAuthor
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getArticles($page, $limit, $status=[Article::STATUS_PUBLISHED], $columns=["*"], $pageName="page", $withAuthor=false)
	{
		$query = Article::whereIn("status", $status);

		if ($withAuthor) {
        	$query->with("author");
        }
		return $query->paginate($limit, $columns, $pageName, $page);
	}
}