<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\WorkTime;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'file',
        'status',
        'project_id'

    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function worktimes(){
        return $this->hasMany(WorkTime::class);
    }
}
