<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test post users api.
     *
     * @return void
     */
    public function testPostUsersApi()
    {
        $response = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12344",
            "pass_verify" => "12345",
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12345",
            "pass_verify" => "12345",
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);

        $response = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12345",
            "pass_verify" => "12345",
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtifyy@gmail.com",
            "pass" => "12344",
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);
    }

    /**
     * Test get users api.
     * 
     * @return boolean
     */
    public function testGetUsersApi()
    {
        $user = User::where("status", [User::STATUS_ACTIVATED])->first();

        $response = $this->get("/api/users/1234567890");
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/users/" . $user->uid);
        $response->assertResponseStatus(403);
        $response->seeJson(["success" => false]);

        $this->actingAs($user);

        $response = $this->get("/api/users/1234567890");
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/users/" . $user->uid);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
        
        if ($user->isAdmin()) {
            $user = User::where("status", [User::STATUS_ACTIVATED])->where("uid", "!=", $user->uid)->first();
            $response = $this->get("/api/users/" . $user->uid);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $user = User::where("status", [User::STATUS_ACTIVATED])->where("uid", "!=", $user->uid)->first();
            $response = $this->get("/api/users/" . $user->uid);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }

    /**
     * Test put users api.
     *
     * @return void
     */
    public function testPutUsersApi()
    {
        $user = User::where("status", [User::STATUS_ACTIVATED])->first();
        $response = $this->put("/api/users/" . $user->uid, [
            "name" => "numtify_test_put"
        ]);
        $response->assertResponseStatus(403);
        $response->seeJson(["success" => false]);

        $this->actingAs($user, "api");

        $response = $this->put("/api/users/" . $user->uid, [
            "name" => "numtify_test_put"
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
        
        $response = $this->put("/api/users/" . $user->uid, [
            "email" => "numtify_test_put"
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->put("/api/users/" . $user->uid, [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12345",
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->put("/api/users/" . $user->uid, [
            "pass" => "12345",
            "pass_verify" => "12345"
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
    }
}
