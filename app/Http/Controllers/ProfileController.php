<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Traits\GeneralTrait;
use App\Traits\ImageTrait;

class ProfileController extends Controller
{
    use GeneralTrait,ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Profile::all();
        return $this->returnData('profile', $profile);
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
    //public function store(Request $request)
    public function store(ProfileRequest $request)
    {
        $photo=$request->file('photo');
        $path=$this->saveImage($photo,'profiles');

         $user=Auth::user();

         $profile=$user->profile()->create([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'photo'=>$path,

         ]);
         return $this->returnData('profile', $profile);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $profile = Profile::find($id);
        if ($profile)
            return $this->returnData('profile', $profile);
        else
            return $this->returnError("", "not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(ProfileRequest $request,$id)
    {
        $profile = Profile::find($id);
        if ($profile) {

                $this->deleteImage($profile->photo);

                $photo=$request->file('photo');
                $path=$this->saveImage($photo,'profiles');

                $profile->update([
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'photo'=>$path,
                    'user_id'=>Auth::id()
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
        $profile = Profile::find($id);
        if ($profile) {
            $this->deleteImage($profile->photo);
            $profile->delete();
            return $this->returnSuccessMessage('delete successfully');
        }
        else
            return $this->returnError("", "not found");
    }
}
