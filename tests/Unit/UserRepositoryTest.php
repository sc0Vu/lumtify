<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Repositories\UserRepository;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get user uid
     *
     * @return void
     */
    public function testGetUserUid()
    {
        $user = new User;
        $repository = new UserRepository($user);
        $uid = $repository->makeUid(32);
        $this->assertEquals(mb_strlen($uid), 32);
        $this->assertNull($user->where("uid", $uid)->first());
    }
}
