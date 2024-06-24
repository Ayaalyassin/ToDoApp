<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$permission): Response
    {
        $user=Auth::user();
        $permission=Permission::where('name',$request->permission)->pluck('name');

        $userroles=$user->roles()->pluck('name')->toArray();
        $userrole=Role::whereIn('name',$userroles)->get();

        foreach($userrole as $role)

        {
            $permissionroles=$role->permissions()->where('name','=',$permission)->pluck('name');
            if($permissionroles == $permission)
                return $next($request);
            else
            return response()->json("You dont have the right permission");
        }

    }
}
