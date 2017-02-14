<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Role;
use App\Repositories\RoleRepository;

class RoleRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test get role.
     *
     * @return void
     */
    public function testGetRole()
    {
        $role = new Role;
        $repository = new RoleRepository();
        $this->assertEquals($repository->getRole("admin"), $role->where("name", "admin")->get());
        $this->assertEquals($repository->getRole("admin", true), $role->where("name", "admin")->with("users")->get());
    }
}
