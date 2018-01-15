<?php

namespace App\Observers;

use App\Models\Lists;

class ListsObserver
{
    public function creating(Lists $list)
    {
        $list->owner_id = auth()->check() ? auth()->user()->id : 0;
    }
}