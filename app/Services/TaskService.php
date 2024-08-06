<?php


namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Traits\FileTrait;
use App\Traits\GeneralTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskService
{
    use GeneralTrait,ImageTrait,FileTrait;

    public function index($project_id)
    {
        $project=Project::find($project_id);
        if(!$project)
        {
            throw new HttpResponseException($this->returnError(404, "project not found"));
        }
        $tasks=$project->tasks()->get();
        return $tasks;
    }

    public function store($request)
    {
        $project=Project::where('id','=', $request->project_id)
            ->first();
        if(is_null($project))
        {
            throw new HttpResponseException($this->returnError(404, "project not found"));
        }

        $file=$request->file('file');
        $path=$this->saveFile($file,'taskfiles');

        $task=$project->tasks()->create([
            'title'=>$request->title,
            'description'=>$request->description,
            'file'=>$path,
            'status'=>$request->status,
        ]);

        return $task;
    }

    public function show($id)
    {
        $task = Task::find($id);
        if (!$task)
            return $this->returnError(404, "not found");
        return $task;
    }

    public function update($request,$id)
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
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task) {
            $this->deleteFile($task->file);
            $task->delete();
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }
}
