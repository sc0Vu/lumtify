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
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
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
            if ($user = $this->auth->parseToken()->authenticate()) {
                return $next($request);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => $e->getMessage(),
                "success" => false
            ], 190);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => $e->getMessage(),
                "success" => false
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "errs" => [],
                "errFor" => [],
                "msg" => $e->getMessage(),
                "success" => false
            ], 401);
        }
        return response()->json([
            "errs" => [],
            "errFor" => [],
            "msg" => 'Unauthorized.',
            "success" => false
        ], 401);
    }
}
