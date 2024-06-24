<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Traits\GeneralTrait;


class WorkTimeController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($task_id)
    {
        $worktimes= WorkTime::where('task_id','=',$task_id)
        ->get();
        return $this->returnData('worktimes', $worktimes);
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
    public function store(Request $request)
    {
        $this->validate($request,[
            'task'=>'required|integer',
            'start'=>'required',
            'pause'=>'required',
            'end'=>'required',
            'finish'=>'required',
        ]);
        $task=Task::where('id','=', $request->task)
        ->first();
        if(is_null($task))
        {
             return response()->json("task not found");
        }



        $worktime=$task->worktimes()->create([
            'start'=>$request->start,
            'pause'=>$request->pause,
            'end'=>$request->end,
            'finish'=>$request->finish
        ]);

        return $this->returnData('worktime', $worktime);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $worktime = WorkTime::find($id);
        if ($worktime)
            return $this->returnData('worktime', $worktime);
        else
            return $this->returnError("", "not found");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkTime $workTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
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
        $worktime = WorkTime::find($id);
        if ($worktime) {
            $worktime->delete();
            return $this->returnSuccessMessage('delete successfully');
        }
         else
            return $this->returnError("", "not found");
    }
}
