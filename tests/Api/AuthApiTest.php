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
        // $request->seeJson();
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
        // $request->seeJson();
    }
}