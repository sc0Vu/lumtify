<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Category;
use App\Repositories\CategoryRepository;

class CategoryApiTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test get categories api.
     *
     * @return void
     */
    public function testGetCategories()
    {
        $response = $this->get("/api/categories");
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
    }

    /**
     * Test post categories api.
     *
     * @return void
     */
    public function testPostCategoriesApi()
    {
        $user = User::where("id", 1)->first();
        $response = $this->post("/api/categories", [
            "name" => "Hellolumtify!",
            "slug" => "lumtify",
        ]);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);

        if ($user->isAdmin() || $user->isEditor()) {
            $response = $this->post("/api/categories", [
                "name" => "Hellolumtify!",
                "slug" => "lumtify!@#$%^&*()",
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(400);
            $response->seeJson(["success" => false]);

            $response = $this->post("/api/categories", [
                "name" => "Hellolumtify!",
                "slug" => "lumtify",
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $response = $this->post("/api/categories", [
                "name" => "Hellolumtify!",
                "slug" => "lumtify",
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }

    /**
     * Test get category api.
     *
     * @return void
     */
    public function testGetCategory()
    {
        $category = Category::first();

        $response = $this->get("/api/categories/+&=  %20");
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/categories/123456789");
        $response->assertResponseStatus(404);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/categories/" . $category->slug);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
    }

    /**
     * Test put categories api.
     *
     * @return void
     */
    public function testPutCategoriesApi()
    {
        $user = User::where("id", 1)->first();
        $category = Category::first();
        $response = $this->put("/api/categories/" . $category->slug, [
            "title" => "Hello lumtify!",
        ]);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);

        if ($user->isAdmin() || $user->isEditor()) {
            $response = $this->put("/api/categories/" . $category->slug, [
                "name" => "Hello lumtify!",
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $response = $this->put("/api/categories/" . $category->slug, [
                "name" => "Hello lumtify!",
            ], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }

    /**
     * Test delete categories api.
     *
     * @return void
     */
    public function testDeleteCategoriesApi()
    {
        $user = User::where("id", 1)->first();
        $category = Category::first();
        $response = $this->delete("/api/categories/" . $category->slug);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);

        if ($user->isAdmin() || $user->isEditor()) {
            $response = $this->delete("/api/categories/" . $category->slug, [], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $response = $this->delete("/api/categories/" . $category->slug, [], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }
}
