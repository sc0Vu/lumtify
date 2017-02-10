<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserRepository
{
    /**
     * The user model
     * 
     * @var App\User
     */
    protected $user;

    /**
     * construct
     * 
     * @param User $user
     */
	public function __construct(User $user)
	{
	    $this->user = $user;
	}
    
    /**
     * create user
     * 
     * @param  array $data
     * @return 
     */
    public function create($data)
    {
        $uid = $this->makeUid();

        $this->user->uid = $uid;
        $this->user->name = $data["name"];
        $this->user->email = $data["email"];
        $this->user->password = Hash::make($data["pass"]);
        $this->user->status = User::STATUS_ACTIVATED;
        
        try {
            return $this->user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }
    
    /**
     * make user uid
     *
     * @param  integer $length
     * @return string
     */
	public function makeUid($length=32)
	{
		$uid = Str::random($length);
		$user = $this->user->where("uid", $uid)->first();

		if (empty($user)) {
			return $uid;
		}
		return $this->makeUid($length);
	}
}