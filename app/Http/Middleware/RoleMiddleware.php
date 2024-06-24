<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        $user=Auth::user();

        $roleid=$user->roles()->where('name',$role)->pluck('role_id');

        $rolee=Role::where('name',$role)->pluck('id');

        if($roleid ==$rolee)
        {
            return $next($request);
        }
        else{
            return response()->json("You dont have the right role");
        }
    }
}
