<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test get users api.
     * 
     * @return void
     */
    public function testGetUsersApi()
    {
        $user = User::where("status", [User::STATUS_ACTIVATED])->first();

        $response = $this->get("/api/users");
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        if ($user->isAdmin()) {
            $token = $this->app["auth"]->guard("api")->fromUser($user);
            $response = $this->get("/api/users?per=10", [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
            $result = $response->response->getData(true);
            $this->assertEquals(count($result["users"]["data"]), 10);
        }
    }

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
     * Test get user api.
     * 
     * @return boolean
     */
    public function testGetUserApi()
    {
        $user = User::where("status", [User::STATUS_ACTIVATED])->first();

        $response = $this->get("/api/users/1234567890");
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/users/" . $user->uid);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);

        $response = $this->get("/api/users/1234567890", [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->get("/api/users/" . $user->uid, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
        
        if ($user->isAdmin()) {
            $user = User::where("status", [User::STATUS_ACTIVATED])->where("uid", "!=", $user->uid)->first();
            
            if (!empty($user)) {
                $response = $this->get("/api/users/" . $user->uid, [
                    'Authorization' => 'Bearer ' . $token
                ]);
                $response->assertResponseStatus(200);
                $response->seeJson(["success" => true]);
            }
        } else {
            $user = User::where("status", [User::STATUS_ACTIVATED])->where("uid", "!=", $user->uid)->first();
            
            if (!empty($user)) {
                $response = $this->get("/api/users/" . $user->uid, [
                    'Authorization' => 'Bearer ' . $token
                ]);
                $response->assertResponseStatus(403);
                $response->seeJson(["success" => false]);
            }
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
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);

        $response = $this->put("/api/users/" . $user->uid, [
            "name" => "numtify_test_put"
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
        
        $response = $this->put("/api/users/" . $user->uid, [
            "email" => "numtify_test_put"
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->put("/api/users/" . $user->uid, [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12345",
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->put("/api/users/" . $user->uid, [
            "pass" => "12345",
            "pass_verify" => "12345"
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);
    }

    /**
     * Test delete users api.
     *
     * @return void
     */
    public function testDeleteUsersApi()
    {
        $user = User::where("status", [User::STATUS_ACTIVATED])->first();
        $response = $this->delete("/api/users/" . $user->uid);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        $token = $this->app["auth"]->guard("api")->fromUser($user);
        
        $response = $this->delete("/api/users/" . $user->uid, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(403);
        $response->seeJson(["success" => false]);
        $userA = User::where("uid", "!=", $user->uid)->first();

        if ($user->isAdmin()) {
            $response = $this->delete("/api/users/" . $userA->uid, [], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $response = $this->delete("/api/users/" . $userA->uid, [], [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }
}
