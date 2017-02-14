<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
	/**
	 * get role users by name
	 * 
	 * @param  string $roleName
	 * @param  boolean $withUsers
	 * @return 
	 */
	public function getRole($roleName, $withUsers=false)
	{
		$query = Role::where("name", $roleName);
        
        if ($withUsers) {
        	$query->with("users");
        }
		return $query->get();
	}
}