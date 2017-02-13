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
        $response = $this->get('/api/auth/login');
        $response->assertResponseStatus(405);
        $response->seeJson(["success" => false]);

        $response = $this->post('/api/auth/login', []);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->post('/api/auth/login', [
            "email" => "not_existed_email_1@gmail.com",
            "password" => "12345"
        ]);
        $response->assertResponseStatus(400);
        $response->seeJson(["success" => false]);

        $response = $this->post('/api/auth/login', [
            "email" => "not_existed_email_1@gmail.com",
            "password" => "ilovelumtify"
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);

        $token = $response->response->getData(true)['token'];
        $response = $this->get('/api/auth/user', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);

        $response = $this->get('/api/auth/logout', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(200);
        $response->seeJson(["success" => true]);

        $response = $this->get('/api/auth/user', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);
    }
}