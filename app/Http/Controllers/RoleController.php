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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return $this->returnData('roles', $roles);
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
    public function store(Request $request)
    {

        $role=Role::create([
           'name'=>$request->name
        ]);

        return $this->returnData('role', $role);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::find($id);
        if ($role)
            return $this->returnData('role', $role);
        else
            return $this->returnError("", "not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
