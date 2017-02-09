<?php

use Illuminate\Database\Seeder;
// use App\RoleAssign;

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

        for ($i=1; $i<=2; $i++) {
        	$authAssigns[] =[
                "user_id" => $i,
                "role_id" => $i
            ];
        }
        
        // RoleAssign::insert($authAssigns);
    }
}
