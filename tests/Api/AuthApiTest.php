<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test login api
     *
     * @return void
     */
    public function testLoginApi()
    {
        $request = $this->get('/api/auth/login');
        $request->assertResponseStatus(405);
        $request->seeJson();

        $request = $this->post('/api/auth/login', []);
        $request->assertResponseStatus(400);
        $request->seeJson();

        $request = $this->post('/api/auth/login', [
            "email" => "not_existed_email_1@gmail.com",
            "password" => "12345"
        ]);
        $request->assertResponseStatus(400);
        $request->seeJson();

        $request = $this->post('/api/auth/login', [
            "email" => "not_existed_email_1@gmail.com",
            "password" => "ilovelumtify"
        ]);
        $request->assertResponseStatus(200);
        $request->seeJson(["token"]);
    }

    /**
     * Test logout api
     *
     * @return void
     */
    public function testLogoutApi()
    {
        $request = $this->get('/api/auth/logout');
        $request->assertResponseStatus(405);
        $request->seeJson();
    }
}