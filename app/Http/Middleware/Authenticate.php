<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Models\User;
use Auth;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next) {
        if(isset($request->user()->id)){
            $userid     = $request->user()->id;
            $userstatus = $request->user()->status;
            if($userstatus == 0){
                 Auth::logout();
                 return redirect()->guest('/');
            }
            $userdata   = User::where('id', $userid)->get();
            if($userdata){
                return $next($request);
            }
        }
        Auth::logout();
        return redirect()->guest('/');
    }

    protected function redirectTo($request) {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
