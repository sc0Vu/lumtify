<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
	/**
     * Get roles.
     * 
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     * @return \App\Role
     */
    public function roles($perPage = 10, $columns = ['*'], $pageName = 'page', $page = 1)
    {
        return Role::paginate($perPage, $columns, $pageName, $page);
    }

	/**
	 * Get role.
	 * 
	 * @param string $roleName
	 * @param boolean $withUsers
	 * @return \Illuminate\Support\Collection
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