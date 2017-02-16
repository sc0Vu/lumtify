<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Article;
use App\Repositories\ArticleRepository;

class ArticleApiTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test get articles api.
     *
     * @return void
     */
    public function testGetArticles()
    {
        $response = $this->get("/api/articles");
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
    }

    /**
     * Test post articles api.
     *
     * @return void
     */
    public function testPostArticlesApi()
    {
        $user = User::where("id", 1)->first();
        $response = $this->post("/api/articles", [
            "title" => "Hello lumtify!",
            "link" => "yotest",
            "short_description" => "Hello lumtify!",
            "content" => "Hello lumtify!",
            "thumbnail" => "https://blog.ptrgl.com",
            "status" => 1
        ]);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);
        if ($user->isAdmin() || $user->isEditor()) {
            $response = $this->post("/api/articles", [
                "title" => "Hello lumtify!",
                "link" => "yotest",
                "short_description" => "Hello lumtify!",
                "content" => "Hello lumtify!",
                "thumbnail" => "https://blog.ptrgl.com",
                "status" => 4
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(400);
            $response->seeJson(["success" => false]);

            $response = $this->post("/api/articles", [
                "title" => "Hello lumtify!",
                "link" => "yotest",
                "short_description" => "Hello lumtify!",
                "content" => "Hello lumtify!",
                "thumbnail" => "https://blog.ptrgl.com",
                "status" => 1
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $response = $this->post("/api/articles", [
                "title" => "Hello lumtify!",
                "link" => "yotest",
                "short_description" => "Hello lumtify!",
                "content" => "Hello lumtify!",
                "thumbnail" => "https://blog.ptrgl.com",
                "status" => 1
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }

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
}
