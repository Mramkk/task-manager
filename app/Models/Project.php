<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, "project_id", "id");
    }
    public function pendingTasks()
    {
        return $this->hasMany(Task::class, "project_id", "id")
            ->where("status", "pending");
    }
    public function completedTasks()
    {
        return $this->hasMany(Task::class, "project_id", "id")
            ->where("status", "completed");
    }
}
