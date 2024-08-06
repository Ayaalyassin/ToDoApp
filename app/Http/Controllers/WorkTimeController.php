<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkTimeRequest;
use App\Services\WorkTimeService;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;


class WorkTimeController extends Controller
{
    use GeneralTrait;

    protected $workTimeService;

    public function __construct(WorkTimeService $workTimeService)
    {
        $this->workTimeService=$workTimeService;
    }

    public function index($task_id)
    {
        $worktimes=$this->workTimeService->index($task_id);
        return $this->returnData('worktimes', $worktimes);
    }

    public function store(WorkTimeRequest $request)
    {
        $worktime=$this->workTimeService->store($request);
        return $this->returnData('worktime', $worktime);
    }


    public function show($id)
    {
        $worktime = $this->workTimeService->show($id);
        return $this->returnData('worktime', $worktime);
    }


    public function update(Request $request,$id)
    {
        $this->workTimeService->update($request,$id);
        return $this->returnSuccessMessage('updated successfully');

    }


    public function destroy($id)
    {
        $this->workTimeService->destroy($id);
        return $this->returnSuccessMessage('delete successfully');

    }
}
