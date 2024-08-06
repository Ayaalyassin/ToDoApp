<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    use GeneralTrait;

    public function getAll()
    {
        $projects = Project::all();
        return $projects;
    }

    public function store($request)
    {
        $user_id=Auth::id();

        $project=Project::create([
            'name'=>$request->name,
            'time'=>$request->time,
            'createdby'=>$user_id
        ]);
        return $project;
    }

    public function show($id)
    {
        $project = Project::find($id);
        if (!$project)
            throw new HttpResponseException($this->returnError(404, "not found"));

        return $project;
    }

    public function update($request,$id)
    {
        $project = Project::find($id);
        if ($project) {

            $existingusers=$project->users->pluck('id')->toArray();

            $users=User::whereIn('name',$request->users_name)->get();
            if(is_null($users))
            {
                throw new HttpResponseException($this->returnError(404, "user not found"));
            }

            $data=[];
            foreach($users as $user)
            {
                $data[]=[
                    'user_id'=>$user->id
                ];
            }

            $project->users()->detach($existingusers);

            $project->users()->attach($data);
            $project->update([
                'name'=>$request->name,
                'time'=>$request->time,
            ]);
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }

    public function adduser($request,$id)
    {
        $user_id=User::where('email','=',$request->email)->pluck('id');

        $project=Project::find($id);
        if($project == null)
        {
            throw new HttpResponseException($this->returnError(404, "not found"));
        }

        $project->users()->sync($user_id);
    }
}
