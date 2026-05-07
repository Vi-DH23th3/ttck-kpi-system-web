<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class CheckUserInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        if(empty(Auth::user()->don_vi_id) || empty(Auth::user()->chuc_vu_id)){
            return redirect()->route('bosungtt')->with('error', 'Bạn phải bổ sung đơn vị hoặc chức vụ mới được truy cập!');
        }
        return $next($request);
    }
}
