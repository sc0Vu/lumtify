<?php

namespace App\Repositories;

use App\Article;

class ArticleRepository
{
	/**
	 * Get articles.
	 * 
	 * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     * @param array $status
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function articles($perPage = 10, $columns = ['*'], $pageName = 'page', $page = 1, $status=[Article::STATUS_PUBLISHED])
	{
		if (!is_array($status)) {
			$status = [$status];
		}
		return Article::whereIn("status", $status)->with("author")->paginate($perPage, $columns, $pageName, $page);
	}

	/**
     * Create article.
     * 
     * @param  array $data
     * @return 
     */
    public function create($data)
    {
        $article = new Article;
        $article->user_id = $data["user_id"];
        $article->title = $data["title"];
        $article->short_description = $data["short_description"];
        $article->content = $data["content"];
        $article->thumbnail = $data["thumbnail"];
        $article->status = $data["status"];
        
        try {
            return $article->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

	/**
	 * Get article.
	 * 
	 * @param  string $link
	 * @param  integer $status
	 * @return array || null
	 */
	public function read($link, $status=Article::STATUS_PUBLISHED)
	{
		return Article::where("link", $link)->where("status", $status)->with("author")->first();
	}
}