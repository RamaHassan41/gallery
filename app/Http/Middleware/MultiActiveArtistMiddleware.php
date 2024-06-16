<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MultiActiveArtistMiddleware
{
    use GeneralTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $status): Response
    {
        $artist=Auth::guard('artist_api')->user();
        $artist_status=$artist->status;
        $arrayStatus=is_array($status)?$status:explode('|',$status);
        foreach ($arrayStatus as $status){
            if($status==$artist_status){
                return $next($request);
            }
        }
        return $this->sendError('Your account status is rejected for doing this action',400);
    }
}
