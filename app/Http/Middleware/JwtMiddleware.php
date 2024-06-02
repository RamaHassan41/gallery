<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user=JWTAuth::parseToken()->authenticate();
        }
        catch(Exception $e){
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return ('Token is invalid');
            }
            elseif($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return ('Token is expired');
            }
            else{
                return ('Authorization token is not found');
            }
        }
        return $next($request);
    }
}
