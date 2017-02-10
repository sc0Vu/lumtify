<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    // }
    
    /**
     * create user api
     * 
     * @param  Request $request
     * @return json
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
        $user = new User;
        $repository = new UserRepository($user);
        
        if ($repository->create($data)) {
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
     * validate create request data
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
}
