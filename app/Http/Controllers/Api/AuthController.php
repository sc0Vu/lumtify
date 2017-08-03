<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * The jwt auth instance.
     * 
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @param  JWTAuth $auth
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * login user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$data = $request->only('email', 'password');
    	$validator = $this->validateLogin($data);

    	if ($validator->fails()) {
    		return response()->json([
    			"errs" => [],
    			"errFor" => $validator->errors(),
    			"msg" => trans("info.failed.validate"),
                "success" => false
    		], 400);
    	}
    	$auth = $this->auth;

        try {
            if (!$token = $auth->attempt($data)) {
                return response()->json([
                    "errs" => [],
                    "errFor" => $validator->errors(),
                    "msg" => trans("info.failed.login"),
                    "success" => false
                ], 400);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "errs" => [
                    $e->getMessage()
                ],
                "errFor" => [],
                "msg" => trans("http.status.500"),
                "success" => false
            ], 500);
        }
    	return response()->json([
    		"errs" => [],
    		"errFor" => $validator->errors(),
    		"msg" => trans("info.success.login"),
    		"token" => $token,
            "success" => true
    	]);
    }

    /**
     * logout
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $this->auth->invalidate(true);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "errs" => [
                    $e->getMessage()
                ],
                "errFor" => [],
                "msg" => trans("http.status.500"),
                "success" => false
            ], 500);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "success" => true
        ]);
    }

    /**
     * user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $this->auth->user();
        $roles = $user->roles()->with("role")->get()->pluck("role")->pluck("name");
    	return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "user" => $user,
            "roles" => $roles,
            "success" => true
        ]);
    }
    
    /**
     * Refresh token.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = $this->auth->parseToken();
            $token = $token->refresh();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "errs" => [
                    $e->getMessage()
                ],
                "errFor" => [],
                "msg" => trans("http.status.500"),
                "success" => false
            ], 500);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => "",
            "token" => $token,
            "success" => true
        ]);
    }

    /**
     * validate login
     * 
     * @param array $data
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
}
