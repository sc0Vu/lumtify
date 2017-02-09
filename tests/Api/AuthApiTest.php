<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get auth api
     *
     * @return void
     */
    public function testGetAuthApi()
    {
        $request = $this->get('/api/users/register');
        $request->assertResponseStatus(405);
        $request->seeJson();

        $request = $this->get('/api/users/login');
        $request->assertResponseStatus(405);
        $request->seeJson();

        $request = $this->get('/api/users/logout');
        $request->assertResponseStatus(405);
        
    }
}
