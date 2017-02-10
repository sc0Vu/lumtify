<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\User;

class AuthController extends Controller
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
     * login user
     * 
     * @param  Request $request
     * @return json
     */
    public function login(Request $request)
    {
    	$data = $request->all();
    	$validator = $this->validateLogin($data);

    	if ($validator->fails()) {
    		return response()->json([
    			"errs" => [],
    			"errFor" => $validator->errors(),
    			"msg" => trans("info.failed.validate"),
                "success" => false
    		], 400);
    	}

    	$guard = $this->getGuard();

    	if ($guard->attempt($data["email"], $data["password"])) {
    		$token = $guard->token();

    		return response()->json([
    			"errs" => [],
    			"errFor" => $validator->errors(),
    			"msg" => trans("info.success.login"),
    			"token" => $token,
                "success" => true
    		]);
    	}
    	return response()->json([
    		"errs" => [],
    		"errFor" => [],
    		"msg" => trans("info.failed.login"),
            "success" => false
    	], 400);
    }

    /**
     * logout user
     * 
     * @param  Request $request
     * @return json
     */
    public function logout(Request $request)
    {
    	Auth::logout();
    }

    /**
     * validate login
     * 
     * @param  array $data
     * @return Validator
     */
    protected function validateLogin($data)
    {
    	return Validator::make($data, [
    		"email" => "required|email",
    		"password" => "required"
    	]);
    }

    /**
     * validate logout
     * 
     * @param  array $data
     * @return Validator
     */
    // protected function validateLogout($data)
    // {}
    
    /**
     * get guard
     * 
     * @return 
     */
    protected function getGuard()
    {
    	return Auth::guard('api');
    }
}
