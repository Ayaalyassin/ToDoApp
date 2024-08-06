<?php


namespace App\Services;

use App\Models\Task;
use App\Models\WorkTime;
use App\Traits\GeneralTrait;
use Illuminate\Http\Exceptions\HttpResponseException;

class WorkTimeService
{
    use GeneralTrait;
    public function index($task_id)
    {
        $task= Task::find($task_id);
        if(!$task)
            throw new HttpResponseException($this->returnError(404, "not found"));
        $worktimes=$task->worktimes()->get();

        return $worktimes;
    }

    public function store($request)
    {
        $task=Task::where('id','=', $request->task)
            ->first();
        if(is_null($task))
        {
            throw new HttpResponseException($this->returnError(404, "not found"));
        }

        $worktime=$task->worktimes()->create([
            'start'=>$request->start,
            'pause'=>$request->pause,
            'end'=>$request->end,
            'finish'=>$request->finish
        ]);

        return $worktime;
    }

    public function show($id)
    {
        $worktime = WorkTime::find($id);
        if (!$worktime)
            throw new HttpResponseException($this->returnError(404, "not found"));
        return $worktime;
    }

    public function update($request,$id)
    {
        $worktime = WorkTime::find($id);
        if ($worktime)
        {
            $worktime->update([
                'start'=>$request->start,
                'pause'=>$request->pause,
                'end'=>$request->end,
                'finish'=>$request->finish
            ]);
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }

    public function destroy($id)
    {
        $worktime = WorkTime::find($id);
        if ($worktime) {
            $worktime->delete();
        }
        else
            throw new HttpResponseException($this->returnError(404, "not found"));
    }
}
