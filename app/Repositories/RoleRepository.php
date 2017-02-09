<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
    /**
     * The role model
     * 
     * @var App\Role
     */
    protected $role;

    /**
     * construct
     * 
     * @param Role $role
     */
	public function __construct(Role $role)
	{
	    $this->role = $role;
	}

	/**
	 * get role users by name
	 * 
	 * @param  string $roleName
	 * @param  boolean $withUsers
	 * @return 
	 */
	public function getRole($roleName, $withUsers=false)
	{
		$query = $this->role->where("name", $roleName);
        
        if ($withUsers) {
        	$query->with("users");
        }
		return $query->get();
	}
}