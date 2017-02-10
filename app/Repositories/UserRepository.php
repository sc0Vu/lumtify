<?php

namespace App\Repositories;

use Illuminate\Support\Str;
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
     * make user uid
     *
     * @param  integer $length
     * @return string
     */
	public function makeUid($length)
	{
		$uid = Str::random($length);
		$user = $this->user->where("uid", $uid)->first();

		if (empty($user)) {
			return $uid;
		}
		return $this->makeUid($length);
	}
}