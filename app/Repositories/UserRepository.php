<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserRepository
{   
    /**
     * Create user.
     * 
     * @param  array $data
     * @return  mixed
     */
    public function create($data)
    {
        $user = new User;
        $uid = $this->makeUid();

        $user->uid = $uid;
        $user->name = $data["name"];
        $user->email = $data["email"];
        $user->password = Hash::make($data["pass"]);
        $user->status = User::STATUS_ACTIVATED;
        
        try {
            return $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    /**
     * Get user.
     * 
     * @param  string $uid
     * @param  array $status
     * @return  \App\User
     */
    public function getUser($uid="", $status=[User::STATUS_ACTIVATED])
    {
        return User::where("uid", $uid)->whereIn("status", $status)->first();
    }
    
    /**
     * Make user uid.
     *
     * @param  integer $length
     * @return string
     */
	public function makeUid($length=32)
	{
		$uid = Str::random($length);
		$user = User::where("uid", $uid)->first();

		if (empty($user)) {
			return $uid;
		}
		return $this->makeUid($length);
	}
}