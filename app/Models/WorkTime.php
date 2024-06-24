<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'pause',
        'end',
        'finish',
        'task_id'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }
}
