<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Repositories\UserRepository;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test make uid.
     *
     * @return void
     */
    public function testMakeUid()
    {
        $repository = new UserRepository();
        $uid = $repository->makeUid(32);
        $this->assertEquals(mb_strlen($uid), 32);
        $this->assertNull(User::where("uid", $uid)->first());
    }

    /**
     * Test create.
     * 
     * @return boolean
     */
    public function testCreate()
    {
        $repository = new UserRepository();
        $result = $repository->create([
            "name" => "lumtify",
            "email" => "lumtify12345@gmail.com",
            "pass" => "lumtifylumtify"
        ]);
        $this->assertTrue($result);

        $result = $repository->create([
            "name" => "lumtify",
            "email" => "lumtify12345@gmail.com",
            "pass" => "lumtifylumtify"
        ]);
        $this->assertFalse($result);
    }

    /**
     * Test get user.
     * 
     * @return boolean
     */
    public function testGetUser()
    {
        $user = User::where("status", User::STATUS_ACTIVATED)->first();

        $repository = new UserRepository();
        $result = $repository->getUser($user->uid);
        $this->assertEquals($result, $user);

        $result = $repository->getUser("12345678900987654321");
        $this->assertNull($result);
    }
}
