<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $auths = [
            "admin", "editor"
        ];
        $roles = [];

        foreach ($auths as $key => &$auth) {
        	$roles[] = [
        	    "id" => $key + 1,
        	    "name" => $auth
        	];
        }

        Role::insert($roles);
    }
}
