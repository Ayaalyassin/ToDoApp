<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Http\Requests\PermissionRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class PermissionController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return $this->returnData('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {

         $permission=Permission::create([
            'name'=>$request->name
         ]);

         return $this->returnData('permission', $permission);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permission = Permission::find($id);
        if ($permission)
            return $this->returnData('permission', $permission);
        else
            return $this->returnError("", "not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, $id)
    {
        $permission = Permission::find($id);
        if ($permission)
        {
                $permission->update([
                    'name'=>$request->name,
                ]);

                return $this->returnSuccessMessage('updated successfully');
        }
        else
            return $this->returnError("", "permission not found");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->delete();
            return $this->returnSuccessMessage('delete successfully');
        }
         else
            return $this->returnError("", "not found");
    }




    public function getrole(Request $request)
    {
        $permission=Permission::where('name',$request->permission)->first();
        $role_id=$permission->roles()->pluck('role_id');
        return response()->json($role_id);
        $role=Role::where('id',$role_id)->first();
        return response()->json($role);
    }




    public function addpermissiontorole(PermissionRoleRequest $request)
    {
         $role=Role::where('name',$request->role)->first();
         //$permission=Permission::where('name',$request->name)->first();
         //return response()->json($role);
         if(is_null($role))
         {
            return $this->returnError("", "role not found");
         }
         $permission=Permission::create([
            'name'=>$request->name
         ]);
         //return response()->json($permission->id);

        $role->permissions()->sync($permission->id);

         return $this->returnData('permission', $permission);
    }



    public function deletepermissionfromrole(Request $request,$id)
    {
        $this->validate($request,[
            'role'=>'string|required'
        ]);
        $permission = Permission::find($id);
        $role=Role::where('name',$request->role)->first();

        if(!$role)
        {
            return $this->returnError("", "role not found");
        }

        if($permission)
        {
            $role->permissions()->detach($permission->id);
            return $this->returnSuccessMessage('deleted successfully');
        }
        else
        return $this->returnError("", "permission not found");
    }




    public function updatepermissionfromrole(PermissionRoleRequest $request, $id)
    {
        $permission = Permission::find($id);

        if(!$permission)
        {
            return $this->returnError("", "permission not found");
        }


        $role=Role::where('name',$request->role)->first();

        $role->permissions()->detach($permission->id);

        if(!$role)
        {
            return $this->returnError("", "role not found");
        }
        if ($permission)
        {
                $permission->update([
                    'name'=>$request->name,
                ]);
                $role->permissions()->attach($permission->id);
                return $this->returnSuccessMessage('updated successfully');
        }
        else
            return $this->returnError("", "permission not found");
    }







    public function testpermission(Request $request)
    {
        $user=Auth::user();
        $permission=Permission::where('name',$request->permission)->pluck('name');
        $userroles=$user->roles()->pluck('name')->toArray();

        $x=Role::where('name',"admin")->with(array('permissions'=>function($query) {
            $query->get();
        }));





        return response()->json($x);

    }
}
