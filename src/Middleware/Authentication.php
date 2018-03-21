<?php

namespace Loid\Module\Lbb\Middleware;

use Closure;
use DB;

class Authentication{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if (! $request->session()->has('lbb_user')) {
            return response()->json(['status'=>0,'msg'=>'请先登录']);
        } else {
            $request->user = $request->session()->get('lbb_user');
        }
        return $next($request);
    }
    
    
}
