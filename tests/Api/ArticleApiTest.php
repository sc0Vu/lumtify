<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Article;
use App\Repositories\ArticleRepository;

class ArticleApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get article api
     *
     * @return void
     */
    public function testGetArticle()
    {
        $article = new Article;
        $repository = new ArticleRepository($article);
        $this->assertEquals($repository->getArticle("articles80"), $article->where("article80")->first());
        $this->assertEquals($repository->getArticle("articles80", Article::STATUS_DRAFT), $article->where("article80", Article::STATUS_DRAFT)->first());
        $this->assertEquals($repository->getArticle("articles80", Article::STATUS_PUBLISHED, true), $article->where("article80", Article::STATUS_PUBLISHED)->with("author")->first());
    }

    /**
     * Test get articles api
     *
     * @return void
     */
    public function testGetArticles()
    {
        $article = new Article;
        $repository = new ArticleRepository($article);
        $this->assertEquals($repository->getArticles(1, 10, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE]), $article->paginate(10));
        $this->assertEquals($repository->getArticles(1, 10, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE], ["*"], "page", true), $article->with("author")->paginate(10));
    }
}
