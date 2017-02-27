<?php

use Illuminate\Database\Seeder;
use App\RoleAssign;

class RoleAssignsTableSeeder extends Seeder
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
        $authAssigns = [];

        foreach ($auths as $key => &$auth) {
        	$authAssigns[] =[
                "user_id" => $key + 1,
                "role_id" => $key + 1
            ];
        }
        
        RoleAssign::insert($authAssigns);
    }
}
