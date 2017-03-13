<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use DB;
use App\User;
use App\Role;
use App\RoleAssign;

class UserRepository
{
    /**
     * Get users.
     * 
     * @param int $perPage
     * @param array $columns
     * @param string $pageName
     * @param int $page
     * @param array $status
     * @return \App\User
     */
    public function users($perPage = 10, $columns = ['*'], $pageName = 'page', $page = 1, $status=[User::STATUS_ACTIVATED])
    {
        if (!is_array($status)) {
            $status = [$status];
        }
        return User::whereIn("status", $status)->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Create user.
     * 
     * @param array $data
     * @return boolean
     */
    public function create($data)
    {
        $user = new User;
        $uid = $this->makeUid();

        $user->uid = $uid;
        $user->name = $data["name"];
        $user->thumbnail = @$data["thumbnail"];
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
     * @param string $uid
     * @param array $status
     * @return \App\User
     */
    public function read($uid="", $status=[User::STATUS_ACTIVATED])
    {
        if (!is_array($status)) {
            $status = [$status];
        }
        return User::where("uid", $uid)->whereIn("status", $status)->first();
    }

    /**
     * Update user.
     *
     * @param \App\User $user
     * @param array $data
     * @return mixed
     */
    public function update(User $user, $data)
    {
        if (isset($data["name"])) {
            $user->name = $data["name"];
        }
        if (isset($data["thumbnail"])) {
            $user->thumbnail = $data["thumbnail"];
        }
        if (isset($data["email"])) {
            $user->email = $data["email"];
        }
        if (isset($data["pass"])) {
            $user->password = Hash::make($data["pass"]);
        }
        if (isset($data["roles"])) {
            $roles = $user->roles()->with("role")->get();

            foreach ($roles as &$role) {
                $key = array_search($role->role->name, $data["roles"]);

                if (is_int($key) && $key >= 0) {
                    unset($data["roles"][$key]);
                } else {
                    $role->delete();
                }
            }
            foreach ($data["roles"] as &$role) {
                $role = Role::where("name", $role)->first();

                if ($role) {
                    $roleAssign = new RoleAssign;
                    $roleAssign->role_id = $role->id;
                    $roleAssign->user_id = $user->id;
                    $roleAssign->save();
                }
            }
        }
        try {
            return $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }
    
    /**
     * Delete user.
     * 
     * @param \App\User $user
     * @return boolean
     */
    public function delete(User $user)
    {
        DB::beginTransaction();
        $articles = $user->articles();

        if ($articles) {
            $articles->delete();
        }
        if ($user->delete()) {
            DB::commit();
            return true;
        }
        DB::rollback();
        return false;
    }
    
    /**
     * Make user uid.
     *
     * @param integer $length
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