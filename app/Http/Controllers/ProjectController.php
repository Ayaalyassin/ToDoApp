<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserProject;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\GeneralTrait;


class ProjectController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return $this->returnData('projects', $projects);
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
    public function store(ProjectRequest $request)
    {
        $user_id=Auth::id();

         $project=Project::create([
                'name'=>$request->name,
                'time'=>$request->time,
                'createdby'=>$user_id
         ]);

         return $this->returnData('project', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Project::find($id);
        if ($project)
            return $this->returnData('project', $project);
        else
            return $this->returnError("", "not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request,$id)
    {
        $project = Project::find($id);
        if ($project) {

            $existingusers=$project->users->pluck('id')->toArray();

            $users=User::whereIn('name',$request->users_name)->get();
            if(is_null($users))
            {
                return $this->returnError("", "user not found");
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
        $project = Project::find($id);
        if ($project) {
            $project->delete();
            return $this->returnSuccessMessage('delete successfully');
        }
         else
            return $this->returnError("", "not found");
    }



    public function adduser(AddUserProject $request,$id)
    {
         $user_id=User::where('email','=',$request->email)->pluck('id');

         $project=Project::find($id);
         if($project == null)
         {
            return $this->returnError("404", "not found");
         }

         $project->users()->sync($user_id);

         return $this->returnSuccessMessage('add successfully');

    }
}
