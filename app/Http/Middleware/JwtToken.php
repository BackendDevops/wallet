<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if( !$user ) throw new Exception('User Not Found');
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json([
                        'data' => null,
                        'status' => false,
                        'message' => 'Token Invalid',
                        'code' => 401

                    ]
                );
            }else if ($e instanceof TokenExpiredException){
                return response()->json([
                        'data' => null,
                        'status' => false,
                        'message' => 'Token Expired',
                        'code' => 401
                    ]
                );
            }
            else{
                if( $e->getMessage() === 'User Not Found') {
                    return response()->json([
                            "data" => null,
                            "status" => false,
                            "message" => "User Not Found",
                            "code" => 401
                        ]
                    );
                }
                return response()->json([
                        'data' => null,
                        'status' => false,
                        'message' => 'Authorization Token not found',
                        'code' => 401
                    ]
                );
            }
        }
        return $next($request);
    }
}
