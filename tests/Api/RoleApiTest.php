<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class RoleApiTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test get roles api.
     * 
     * @return void
     */
    public function testGetRolesApi()
    {
        $user = User::where("status", [User::STATUS_ACTIVATED])->first();

        $response = $this->get("/api/roles");
        $response->assertResponseStatus(401);
        $response->seeJson(["success" => false]);

        if ($user->isAdmin()) {
            $token = $this->app["auth"]->guard("api")->fromUser($user);
            $response = $this->get("/api/roles", [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(200);
            $response->seeJson(["success" => true]);
        } else {
            $token = $this->app["auth"]->guard("api")->fromUser($user);
            $response = $this->get("/api/roles", [
                'Authorization' => 'Bearer ' . $token
            ]);
            $response->assertResponseStatus(403);
            $response->seeJson(["success" => false]);
        }
    }
}
