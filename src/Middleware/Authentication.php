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
        if (false === $request->user = $this->checkUser($request)) {
            return response()->json(['status'=>0,'msg'=>'请先登录']);
        }
        return $next($request);
    }
    
    private function checkUser($request){
        if (empty($request->header('token'))) {
            return false;
        }
        $user = \Loid\Module\Lbb\Model\LbbUser::where('lbb_user_pwd', $request->header('token'))->first();
        if (empty($user)) {
            return false;
        }
        return $user;
    }
}
