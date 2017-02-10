<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Repositories\UserRepository;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test make uid
     *
     * @return void
     */
    public function testMakeUid()
    {
        $user = new User;
        $repository = new UserRepository($user);
        $uid = $repository->makeUid(32);
        $this->assertEquals(mb_strlen($uid), 32);
        $this->assertNull($user->where("uid", $uid)->first());
    }

    /**
     * test create
     * 
     * @return boolean
     */
    public function testCreate()
    {
        $user = new User;
        $repository = new UserRepository($user);
        $result = $repository->create([
            "name" => "lumtify",
            "email" => "lumtify12345@gmail.com",
            "pass" => "lumtifylumtify"
        ]);
        $this->assertTrue($result);
        $user = new User;
        $repository = new UserRepository($user);
        $result = $repository->create([
            "name" => "lumtify",
            "email" => "lumtify12345@gmail.com",
            "pass" => "lumtifylumtify"
        ]);
        $this->assertFalse($result);
    }
}
