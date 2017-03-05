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
        $auths = [
            "admin", "editor"
        ];
    	$users = [];
        $now = new Carbon;
        $user = new User;
        $repository = new UserRepository($user);

        for ($i=1; $i<=2; $i++) {
            $uid = $repository->makeUid(32);
            $users[] =[
                "id" => $i,
                "uid" => $uid,
                "name" => "iamuser " . $i,
                "thumbnail" => "http://placehold.it/300/09f/fff.png",
                "password" => Hash::make("ilovelumtify"),
                "email" => sprintf("%s@lumtify.com", $auths[$i-1]),
                "status" => 1,
                "created_at" => $now,
                "updated_at" => $now
            ];
        }
        for ($i=3; $i<=80; $i++) {
        	$status = rand(1, 2);
            $uid = $repository->makeUid(32);
        	$users[] =[
                "id" => $i,
                "uid" => $uid,
                "name" => "iamuser " . $i,
                "thumbnail" => "http://placehold.it/300/09f/fff.png",
                "password" => Hash::make("ilovelumtify"),
                "email" => sprintf("not_existed_email_%d@lumtify.com", $i),
                "status" => $status,
                "created_at" => $now,
                "updated_at" => $now
            ];
        }
        
        $user->insert($users);
    }
}
