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
            $pivot = $key + 1;
        	$authAssigns[] =[
                "id" => $pivot,
                "user_id" => $pivot,
                "role_id" => $pivot
            ];
        }
        
        RoleAssign::insert($authAssigns);
    }
}
