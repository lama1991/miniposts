<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class IdsMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) 
        {
          if((Auth::user()->roles()->where('role_name'=='admin') 
          ) And Auth::user()->is_blocked==0)
           {
            return $next($request);
           }
           else
           {
            return redirect('all_users');
           }

        }
        
        else
        {
        return redirect('login');
        }
       
    }
}
