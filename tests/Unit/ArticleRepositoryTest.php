<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Article;
use App\Repositories\ArticleRepository;

class ArticleRepositoryTest extends TestCase
{
    use DatabaseTransactions;
    
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

    /**
     * Test create article.
     * 
     * @return void
     */
    public function testCreate()
    {
        $repository = new ArticleRepository();
        $this->assertTrue($repository->create([
            "user_id" => 1,
            "title" => "Hello lumtify!",
            "link" => "yotest",
            "short_description" => "Hello lumtify!",
            "content" => "Hello lumtify!",
            "thumbnail" => "https://blog.ptrgl.com",
            "status" => 1
        ]));
    }

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
     * Test update article.
     * 
     * @return void
     */
    public function testUpdate()
    {
        $article = Article::first();
        $repository = new ArticleRepository();
        $this->assertTrue($repository->update($article, [
            "title" => "Hello lumtify!",
            "link" => "yotest",
            "short_description" => "Hello lumtify!",
            "content" => "Hello lumtify!",
            "thumbnail" => "https://blog.ptrgl.com",
            "status" => 1
        ]));
    }

    /**
     * Test delete article.
     * 
     * @return void
     */
    public function testDelete()
    {
        $article = Article::first();
        $repository = new ArticleRepository();
        $this->assertTrue($repository->delete($article));
    }
}
