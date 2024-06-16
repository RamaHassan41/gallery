<?php

namespace App\Http\Middleware;
use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
//use Tymon\JWTAuth\Exceptions\JWTException;
//use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
class AssignMiddleware extends BaseMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null) 
    {
        if($guard!=null){
            auth()->shouldUse($guard); //should you user guard / table
            try {
                JWTAuth::parseToken()->authenticate();
                $user=Auth::guard($guard)->user();
                if($user==null)
                    return $this->sendError('Unauthorized User',401);
                else
                    return $next($request);
            }
            catch(Exception $e){
                if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return $this->sendError('Token is invalid',422);
                }
                else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return $this->sendError('Token is expired',400);
                }
                else{
                    return $this->sendError('Token is not found',404);
                }
            }
        }
        else{
            return $this->sendError('Not allowed',401);
        }
    }
}
