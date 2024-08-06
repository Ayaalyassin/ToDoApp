<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $roles = Role::all();
        return $this->returnData('roles', $roles);
    }



    public function store(Request $request)
    {

        $role=Role::create([
           'name'=>$request->name
        ]);

        return $this->returnData('role', $role);
    }


    public function show($id)
    {
        $role = Role::find($id);
        if ($role)
            return $this->returnData('role', $role);
        else
            return $this->returnError("", "not found");
    }


    public function update(Request $request,$id)
    {
        $role =Role::find($id);
        if ($role)
        {
                $role->update([
                    'name'=>$request->name,
                ]);
                return $this->returnSuccessMessage('updated successfully');
        }
        else
            return $this->returnError("", "not found");
    }


    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return $this->returnSuccessMessage('delete successfully');
        }
         else
            return $this->returnError("", "not found");
    }


    public function addtouser(Request $request)
    {
        $user=User::where('email',$request->email)->first();
        $role=Role::where('name',$request->role)->first();
        if(is_null($user))
        {
           return $this->returnError("404", "user not found");
        }
        if(is_null($role))
        {
           return $this->returnError("404", "role not found");
        }

       $user->roles()->sync($role->id);

        return $this->returnData('role', $role);
    }


}
