<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Article;
use App\Repositories\ArticleRepository;

class ArticleApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get article api.
     *
     * @return void
     */
    public function testGetArticle()
    {
        $article = Article::where("status", [Article::STATUS_PUBLISHED])->first();

        $response = $this->get("/api/articles/+&=  %20");
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/articles/123456789");
        $response->assertResponseStatus(404);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/articles/" . $article->link);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
    }

    /**
     * Test get articles api.
     *
     * @return void
     */
    // public function testGetArticles()
    // {
    //     $article = new Article;
    //     $repository = new ArticleRepository($article);
    //     $this->assertEquals($repository->getArticles(1, 10, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE]), $article->paginate(10));
    //     $this->assertEquals($repository->getArticles(1, 10, [Article::STATUS_DRAFT, Article::STATUS_PUBLISHED, Article::STATUS_ARCHIEVE], ["*"], "page", true), $article->with("author")->paginate(10));
    // }
}
