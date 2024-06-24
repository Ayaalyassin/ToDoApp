<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequset;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Traits\GeneralTrait;
use App\Traits\FileTrait;

class TaskController extends Controller
{
    use GeneralTrait,FileTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($project_id)
    {
        $project=Project::find($project_id);
        if(!$project)
        {
            return $this->returnError("404", "project not found");
        }
        $tasks= Task::where('project_id','=',$project_id)
        ->get();
        return $this->returnData('tasks', $tasks);
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
    public function store(TaskRequset $request)
    {
        $project=Project::where('id','=', $request->project_id)
        ->first();
        if(is_null($project))
        {
             return response()->json("project not found");
        }

        $file=$request->file('file');
        $path=$this->saveFile($file,'taskfiles');

        $task=$project->tasks()->create([
            'title'=>$request->title,
            'description'=>$request->description,
            'file'=>$path,
            'status'=>$request->status,
        ]);

        return $this->returnData('task', $task);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::find($id);
        if ($task)
            return $this->returnData('task', $task);
        else
            return $this->returnError("", "not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request,$id)
    {

        $task = Task::find($id);
        if ($task)
        {
                $this->deleteFile($task->file);

                $file=$request->file('file');
                $path=$this->saveFile($file,'taskfiles');

                $task->update([
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'file'=>$path,
                    'status'=>$request->status
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
        $task = Task::find($id);
        if ($task) {
            $this->deleteFile($task->file);
            $task->delete();
            return $this->returnSuccessMessage('delete successfully');
        }
         else
            return $this->returnError("", "not found");
    }
}
