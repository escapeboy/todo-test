<?php

namespace App\Observers;

use App\Models\Tasks;

class TasksObserver
{
    public function creating(Tasks $task)
    {
        $task->owner_id = auth()->check() ? auth()->user()->id : 0;
    }
}