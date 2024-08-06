<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequset;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use App\Traits\GeneralTrait;
use App\Traits\FileTrait;

class TaskController extends Controller
{
    use GeneralTrait,FileTrait;

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService=$taskService;
    }

    public function index($project_id)
    {
        $tasks=$this->taskService->index($project_id);
        return $this->returnData('tasks', $tasks);
    }


    public function store(TaskRequset $request)
    {
        $task=$this->taskService->store($request);

        return $this->returnData('task', $task);
    }


    public function show($id)
    {
        $task = $this->taskService->show($id);

        return $this->returnData('task', $task);
    }


    public function update(TaskUpdateRequest $request,$id)
    {
        $this->taskService->update($request,$id);
        return $this->returnSuccessMessage('updated successfully');

    }


    public function destroy($id)
    {
        $this->taskService->destroy($id);
        return $this->returnSuccessMessage('delete successfully');
    }
}
