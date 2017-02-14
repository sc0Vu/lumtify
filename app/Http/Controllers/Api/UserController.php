<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use Validator;
use App\User;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * The user repository instance.
     * 
     * @var \App\Repository\UserRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Create user api.
     * 
     * @param  \Illuminate\Http\Request $request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = $this->validateCreate($data);

        if ($validator->fails()) {
            return response()->json([
                "errs" => [],
                "errFor" => $validator->errors(),
                "msg" => trans("info.failed.validate"),
                "success" => false
            ], 400);
        }
        if ($this->repository->create($data)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("info.success.create"),
                "success" => true
            ]);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => trans("info.failed.create"),
            "success" => false
        ], 500);
    }

    /**
     * Users.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\JsonResponse
     */
    // public function users(Request $request)
    // {}

    /**
     * Read user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, $uid)
    {
        $repository = $this->repository;

        if (!$this->validateUid($uid)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.400"),
                "success" => false
            ], 400);
        }
        
        $user = $repository->getUser($uid);

        if (empty($user)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.404"),
                "success" => false
            ], 404);
        }
        if (Gate::denies("read", $user)) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => trans("http.status.403"),
                "success" => false
            ], 403);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "user" => $user,
            "success" => true
        ]);
    }
    
    /**
     * Validate create request data.
     * 
     * @param  array $data
     * @return Validator
     */
    protected function validateCreate($data)
    {
        return Validator::make($data, [
            "name" => "required|string|max:255",
            "email" => "required|email|string|max:255|unique:users,email",
            "pass" => "required",
            "pass_verify" => "required|same:pass"
        ]);
    }

    /**
     * Validate user uid.
     * 
     * @param  string $uid
     * @return boolean
     */
    protected function validateUid($uid="")
    {
        if (preg_match("/[a-zA-Z0-9]{32}/", $uid)) {
            return true;
        }
        return false;
    }
}
