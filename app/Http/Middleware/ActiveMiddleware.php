<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveMiddleware
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $status): Response
    {
        // $artist=Auth::guard('artist_api')->user();
        // $artist_status=$artist->status;
        // if($artist_status!=$status){
        //     return $this->sendError('Unauthorized',401);
        // }
        // else{
        //     return $next($request);
        // }


        // $user=Auth::guard('api')->user();
        // if ($user->email_verified_at==null) {
        //     auth()->logout();
        //     return response()->json('message', 'You need to confirm your account. We have sent you an activation code, please check your email.');
        //   }


        // return $next($request);
    }
}
