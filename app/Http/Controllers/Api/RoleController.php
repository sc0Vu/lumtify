<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Gate;
use App\Role;
use App\Repositories\RoleRepository;

class RoleController extends Controller
{
    /**
     * The role repository instance.
     * 
     * @var \App\Repository\RoleRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\RoleRepository $repository
     * @return void
     */
    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Roles.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function roles(Request $request)
    {
        $role = new Role;

        if (Gate::denies("roles", $role)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        $query = $request->query();
        $page = isset($query["page"]) ? (preg_match("/[\d]+/", $query["page"]) ? $query["page"] : 1): 1;
        $per = isset($query["per"]) ? (preg_match("/[\d]+/", $query["per"]) ? $query["per"] : 10): 10;;
        $roles = $this->repository->roles($per, ["*"], "page", $page)->pluck("name");
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "success" => true,
            "roles" => $roles
        ]);
    }
}
