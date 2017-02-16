<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;
use App\Repositories\UserRepository;

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
        $now = new Carbon;
        $user = new User;
        $repository = new UserRepository($user);

        for ($i=1; $i<=80; $i++) {
        	$status = rand(1, 2);
            $uid = $repository->makeUid(32);
        	$users[] =[
                "id" => $i,
                "uid" => $uid,
                "name" => "iamuser " . $i,
                "password" => Hash::make("ilovelumtify"),
                "email" => sprintf("not_existed_email_%d@gmail.com", $i),
                "status" => $status,
                "created_at" => $now,
                "updated_at" => $now
            ];
        }
        
        $user->insert($users);
    }
}
