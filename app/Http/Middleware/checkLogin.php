<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()){
            $user_status = ['admin','writer'];
            if(!in_array(auth()->user()->status,$user_status)){
                Auth::logout();
                return redirect()->route('login');
            }else{
                return $next($request);
            }
        }else{
            Auth::logout();
            return redirect()->route('login');
        }


    }
}
