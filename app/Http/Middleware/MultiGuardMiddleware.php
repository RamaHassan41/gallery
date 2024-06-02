<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class MultiGuardMiddleware extends BaseMiddleware
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next ,  $guards = null): Response
    {
        if($guards != null){
            try {
                $arrayGuards = is_array($guards) ? $guards : explode('|', $guards);
                foreach ($arrayGuards as $guard){
                    auth()->shouldUse($guard);
                    JWTAuth::parseToken()->authenticate();
                    $user = Auth::guard($guard)->user();
                    if($user!=null){
                        return $next($request);
                    }
                }
                 return $this->sendError('Unauthorized User',401);

            }catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return $this->sendError('Token is Invalid',401);

                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return $this->sendError('Token is Expired',401);

                }else{
                    return $this->sendError('Token not found',401);
                }
            }
        }
        else{
            return $this->sendError('Not Allowed',405);

        }
    }
}





