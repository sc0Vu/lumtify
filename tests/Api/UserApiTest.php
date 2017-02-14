<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test post users api
     *
     * @return void
     */
    public function testPostUsersApi()
    {
        $request = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12344",
            "pass_verify" => "12345",
        ]);
        $request->assertResponseStatus(400);
        $request->seeJson(["success" => false]);

        $request = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12345",
            "pass_verify" => "12345",
        ]);
        $request->assertResponseStatus(200);
        $request->seeJson(["success" => true]);

        $request = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtify@gmail.com",
            "pass" => "12345",
            "pass_verify" => "12345",
        ]);
        $request->assertResponseStatus(400);
        $request->seeJson(["success" => false]);

        $request = $this->post("/api/users", [
            "name" => "numtify",
            "email" => "numtifyy@gmail.com",
            "pass" => "12344",
        ]);
        $request->assertResponseStatus(400);
        $request->seeJson(["success" => false]);
    }
}
