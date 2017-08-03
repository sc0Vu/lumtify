<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;

class JWTAuthenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Tymon\JWTAuth\JWTAuth  $auth
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = "api")
    {
        try {
            if ($this->auth->parseToken()->check()) {
                return $next($request);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                "errs" => [
                    $e->getMessage()
                ],
                "errFor" => [],
                "msg" => trans('http.status.190'),
                "success" => false
            ], 190);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                "errs" => [
                    $e->getMessage()
                ],
                "errFor" => [],
                "msg" => trans('http.status.401'),
                "success" => false
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "errs" => [
                    $e->getMessage()
                ],
                "errFor" => [],
                "msg" => trans('http.status.401'),
                "success" => false
            ], 401);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => trans('http.status.401'),
            "success" => false
        ], 401);
    }
}
