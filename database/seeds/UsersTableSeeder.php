<?php

use Illuminate\Database\Seeder;
// use Carbon\Carbon;
// use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = [];

        for ($i=1; $i<=3; $i++) {
        	$status = rand(1, 2);
        	$users[] =[
                "id" => $i,
                "name" => "iamuser ".$i,
                "password" => bcrypt("ilovelumtify"),
                "email" => sprintf("not_existed_email_%d@gmail.com", $i),
                "status" => $status
            ];
        }
        
        // User::insert($users);
    }
}
