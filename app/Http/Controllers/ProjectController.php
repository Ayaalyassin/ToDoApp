<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserProject;
use App\Http\Requests\ProjectRequest;
use App\Services\ProjectService;
use App\Traits\GeneralTrait;


class ProjectController extends Controller
{
    use GeneralTrait;

    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService=$projectService;
    }

    public function index()
    {
        $projects=$this->projectService->getAll();
        return $this->returnData('projects', $projects);
    }



    public function store(ProjectRequest $request)
    {
        $project=$this->projectService->store($request);
         return $this->returnData('project', $project);
    }


    public function show($id)
    {
        $project =$this->projectService->show($id);
        return $this->returnData('project', $project);
    }



    public function update(ProjectRequest $request,$id)
    {
        $this->projectService->update($request,$id);
        return $this->returnSuccessMessage('updated successfully');

    }


    public function destroy($id)
    {
        $this->projectService->destroy($id);

        return $this->returnSuccessMessage('delete successfully');

    }



    public function adduser(AddUserProject $request,$id)
    {
         $this->projectService->adduser($request,$id);

         return $this->returnSuccessMessage('add successfully');

    }
}
