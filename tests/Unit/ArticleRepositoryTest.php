<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Article;
use App\Repositories\ArticleRepository;

class ArticleRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get article.
     *
     * @return void
     */
    public function testGetArticle()
    {
        $article = new Article;
        $repository = new ArticleRepository();
        $this->assertEquals($repository->read("articles80"), $article->where("title", "article80")->first());
        $this->assertEquals($repository->read("articles80", Article::STATUS_DRAFT), $article->where("title", "article80")->where("status", Article::STATUS_DRAFT)->first());
        $this->assertEquals($repository->read("articles80", Article::STATUS_PUBLISHED, true), $article->where("title", "article80")->where("status", Article::STATUS_PUBLISHED)->with("author")->first());
    }

    /**
     * Test get articles.
     *
     * @return void
     */
    public function testGetArticles()
    {
        $article = new Article;
        $repository = new ArticleRepository();
        $result = $repository->articles(10, ["*"], "page", 1, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE]);
        $this->assertEquals(10, count($result));
    }
}
