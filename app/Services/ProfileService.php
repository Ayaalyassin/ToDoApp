<?php


namespace App\Services;

use App\Models\Profile;
use App\Traits\GeneralTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    use GeneralTrait,ImageTrait;
    public function getAll()
    {
        $profile = Profile::all();
        return $profile;
    }

    public function store($request)
    {
        $photo=$request->file('photo');
        $path=$this->saveImage($photo,'profiles');

        $user=Auth::user();

        $profile=$user->profile()->create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'photo'=>$path,

        ]);
        return $profile;
    }

    public function show($id)
    {
        $profile = Profile::find($id);
        if (!$profile)
            throw new HttpResponseException($this->returnError(404, "not found"));
        return $profile;
    }

    public function update($request)
    {
        $user=Auth::user();
        $profile =$user->profile()->first();
        if ($profile) {

            $this->deleteImage($profile->photo);

            $photo=$request->file('photo');
            $path=$this->saveImage($photo,'profiles');

            $profile->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'photo'=>$path,
                'user_id'=>$user->id
            ]);
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }

    public function destroy()
    {
        $user=Auth::user();
        $profile =$user->profile()->first();
        if ($profile) {
            $this->deleteImage($profile->photo);
            $profile->delete();
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }
}
